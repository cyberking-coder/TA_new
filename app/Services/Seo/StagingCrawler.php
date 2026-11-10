<?php

namespace App\Services\Seo;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

/**
 * Minimal staging crawler for the Phase 5 SEO rehearsal.
 *
 * Fetches each URL, measures status + hop count (via Http's redirect chain),
 * parses the rendered HTML and extracts title / meta description / canonical /
 * h1 / JSON-LD counts / images missing alt, so the audit CSV can diff against
 * the PRD's recorded legacy baseline.
 */
class StagingCrawler
{
    /** @return array<string, PageReport> */
    public function crawl(array $urls): array
    {
        $out = [];
        foreach ($urls as $url) {
            try {
                // Disable redirect following so 301s show up as 301 in the report.
                // The hop count is derived from how many Location headers we would
                // have chased to reach a 200.
                $resp = Http::timeout(10)->withOptions(['allow_redirects' => false])->get($url);
            } catch (ConnectionException $e) {
                $out[$url] = new PageReport($url, 0, 0, '', '', 0, '', 0, 0, 0, [$e->getMessage()]);
                continue;
            }

            $html = $resp->body();
            $title = $this->match('/<title[^>]*>([^<]*)<\/title>/i', $html);
            $desc  = $this->matchAttr('/<meta\s+name=["\']description["\'][^>]*>/i', $html, 'content');
            $canon = $this->matchAttr('/<link\s+rel=["\']canonical["\'][^>]*>/i', $html, 'href');
            $h1Count = preg_match_all('/<h1\b[^>]*>/i', $html);
            $jsonLd = preg_match_all('/<script\b[^>]*application\/ld\+json[^>]*>/i', $html);
            $imgTotal = preg_match_all('/<img\b[^>]*>/i', $html, $imgMatches);
            $imgMissingAlt = 0;
            foreach ($imgMatches[0] ?? [] as $tag) {
                if (!preg_match('/\balt\s*=/i', $tag)) $imgMissingAlt++;
            }

            // Guzzle exposes the hop count via the redirect_count stat.
            $hopCount = (int) ($resp->handlerStats()['redirect_count'] ?? 0);

            $out[$url] = new PageReport(
                url: $url,
                status: $resp->status(),
                hops: $hopCount,
                title: trim($title),
                description: trim($desc),
                h1Count: (int) $h1Count,
                canonical: trim($canon),
                jsonLdCount: (int) $jsonLd,
                imagesTotal: (int) $imgTotal,
                imagesMissingAlt: $imgMissingAlt,
                errors: [],
            );
        }
        return $out;
    }

    private function match(string $pattern, string $html): string
    {
        return preg_match($pattern, $html, $m) ? ($m[1] ?? '') : '';
    }

    private function matchAttr(string $tagPattern, string $html, string $attr): string
    {
        if (!preg_match($tagPattern, $html, $m)) return '';
        return preg_match('/\b'.preg_quote($attr, '/').'\s*=\s*["\']([^"\']*)["\']/i', $m[0], $am) ? $am[1] : '';
    }
}
