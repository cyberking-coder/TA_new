<?php

namespace App\Services\Seo;

class AuditCsvWriter
{
    /**
     * Writes a prioritised CSV with the PRD's required columns:
     * URL · evidence · severity · owner · fix · retest.
     *
     * @param array<string,PageReport> $reports
     */
    public function write(string $outPath, array $reports): int
    {
        $dir = dirname($outPath);
        if (!is_dir($dir)) mkdir($dir, 0o775, true);

        $fh = fopen($outPath, 'w');
        fputcsv($fh, ['URL', 'Status', 'Evidence', 'Severity', 'Owner', 'Fix', 'Retest result']);
        $rows = 0;
        foreach ($reports as $r) {
            foreach ($this->findings($r) as $f) {
                fputcsv($fh, [$r->url, $r->status, $f['evidence'], $f['severity'], $f['owner'], $f['fix'], '']);
                $rows++;
            }
        }
        fclose($fh);
        return $rows;
    }

    /** @return list<array{evidence:string, severity:string, owner:string, fix:string}> */
    private function findings(PageReport $r): array
    {
        $f = [];

        if ($r->errors) {
            $f[] = ['evidence' => 'fetch error: '.implode('; ', $r->errors), 'severity' => 'P0', 'owner' => 'dev', 'fix' => 'bring route online'];
            return $f;
        }
        if ($r->status >= 500) {
            $f[] = ['evidence' => 'server error '.$r->status, 'severity' => 'P0', 'owner' => 'dev', 'fix' => 'fix 5xx'];
        }
        if ($r->status === 404) {
            $f[] = ['evidence' => '404', 'severity' => 'P1', 'owner' => 'content', 'fix' => 'confirm URL is intentional; add redirect if migrated'];
        }
        if ($r->hops > 1 && $r->status === 200) {
            $f[] = ['evidence' => "redirect hops={$r->hops}", 'severity' => 'P1', 'owner' => 'dev', 'fix' => 'collapse into a single 301'];
        }
        if ($r->status === 200) {
            if ($r->title === '') $f[] = ['evidence' => 'empty <title>', 'severity' => 'P0', 'owner' => 'content', 'fix' => 'set descriptive title'];
            if ($r->description === '') $f[] = ['evidence' => 'missing meta description', 'severity' => 'P1', 'owner' => 'content', 'fix' => 'write 1-sentence description'];
            if ($r->h1Count === 0) $f[] = ['evidence' => 'no <h1>', 'severity' => 'P0', 'owner' => 'content', 'fix' => 'add single descriptive H1'];
            if ($r->h1Count > 1) $f[] = ['evidence' => "{$r->h1Count} <h1> tags", 'severity' => 'P1', 'owner' => 'dev', 'fix' => 'reduce to exactly one H1'];
            if ($r->canonical === '') $f[] = ['evidence' => 'no <link rel="canonical">', 'severity' => 'P0', 'owner' => 'dev', 'fix' => 'emit absolute canonical'];
            if ($r->jsonLdCount === 0) $f[] = ['evidence' => 'no JSON-LD', 'severity' => 'P1', 'owner' => 'dev', 'fix' => 'add BreadcrumbList / Product+Offer / NewsArticle'];
            if ($r->imagesTotal > 0 && $r->imagesMissingAlt > 0) {
                $f[] = ['evidence' => "{$r->imagesMissingAlt}/{$r->imagesTotal} images missing alt", 'severity' => 'P0', 'owner' => 'content', 'fix' => 'add descriptive alt text (alt="" for decorative only)'];
            }
        }

        if (!$f) {
            $f[] = ['evidence' => 'no findings', 'severity' => '-', 'owner' => '-', 'fix' => '-'];
        }
        return $f;
    }
}
