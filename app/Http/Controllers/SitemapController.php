<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Notice;

/**
 * Sitemap contains ONLY published, indexable, canonical 200 URLs per PRD.
 * Drafts, withdrawn notices, checkout and buyer routes never appear here.
 */
class SitemapController extends Controller
{
    public function index()
    {
        $urls = collect();

        $urls->push(['loc' => url('/'), 'lastmod' => now()]);
        $urls->push(['loc' => url('/notices-and-downloads'), 'lastmod' => now()]);
        $urls->push(['loc' => url('/preparation/previous-papers'), 'lastmod' => now()]);
        $urls->push(['loc' => url('/preparation/practice-papers'), 'lastmod' => now()]);

        foreach (Notice::query()->published()->orderByDesc('published_on')->get(['slug', 'updated_at']) as $n) {
            $urls->push([
                'loc' => url('/notices/'.$n->slug),
                'lastmod' => $n->updated_at,
            ]);
        }

        foreach (Book::query()->published()->get(['slug', 'updated_at']) as $b) {
            $urls->push([
                'loc' => url('/books/'.$b->slug),
                'lastmod' => $b->updated_at,
            ]);
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
        foreach ($urls as $u) {
            $lastmod = $u['lastmod'] ? (is_string($u['lastmod']) ? $u['lastmod'] : $u['lastmod']->toIso8601String()) : null;
            $xml .= "  <url>\n    <loc>".htmlspecialchars($u['loc'])."</loc>\n";
            if ($lastmod) $xml .= "    <lastmod>".$lastmod."</lastmod>\n";
            $xml .= "  </url>\n";
        }
        $xml .= '</urlset>';

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }
}
