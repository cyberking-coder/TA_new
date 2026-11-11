<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\HeroMedia;
use App\Models\NewslineItem;
use App\Models\Notice;
use App\Support\SyntheticFixtures;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        // Lightweight page cache keyed on content state; invalidated by publish/withdraw actions.
        $latest = Cache::remember('home.latest_notices', now()->addMinutes(5), function () {
            return Notice::query()->published()
                ->orderByDesc('published_on')
                ->limit(3)
                ->get(['slug', 'title', 'summary', 'published_on', 'type', 'new_until'])
                ->map(fn ($n) => [
                    'slug' => $n->slug,
                    'title' => $n->title,
                    'summary' => $n->summary,
                    'date' => $n->published_on->toDateString(),
                    'type' => $n->type,
                    // Store as ISO string, never a Carbon instance. Carbon objects
                    // serialise into the cache fine but deserialise as __PHP_Incomplete_Class
                    // after any Carbon upgrade / autoloader change, which blows up the
                    // view layer the next time the cache is read.
                    'new_until' => optional($n->new_until)->toIso8601String(),
                ])
                ->all();
        });

        // If nothing published yet, surface the synthetic fixtures so Phase 1
        // visuals still demo correctly. Production never ships with empty content.
        if (empty($latest)) {
            $latest = SyntheticFixtures::latestNotices(3);
        }

        $featuredBook = Book::query()->published()->with('editions')->first()
            ?? null;

        $ticker = NewslineItem::query()->published()
            ->orderBy('display_order')->orderByDesc('published_on')
            ->limit(8)->get(['title', 'destination_url', 'new_until'])
            ->map(fn ($n) => [
                'title' => $n->title,
                'url' => $n->destination_url,
                'new_until' => optional($n->new_until)->toIso8601String(),
            ])
            ->all();

        $slides = HeroMedia::activeSlides()
            ->map(fn (HeroMedia $h) => [
                'url' => $h->publicUrl(),
                'alt' => $h->alt_text ?? '',
                'duration' => max(2, $h->duration_seconds ?? 5),
            ])
            ->filter(fn ($s) => !empty($s['url']))
            ->values()
            ->all();

        return view('home.index', [
            'title' => 'Territorial Army exam guide — notices, preparation and the book',
            'description' => 'Dated recruitment notices, preparation resources, and the Territorial Army Commission examination book. Independent publisher, source-attributed notices, buyer-only study access.',
            'canonical' => url('/'),
            'latestNotices' => $latest,
            'featuredBook' => $featuredBook,
            'tickerItems' => $ticker,
            'heroSlides' => $slides,
            'jsonLd' => [
                '@context' => 'https://schema.org',
                '@type' => 'WebSite',
                'url' => url('/'),
                'name' => 'Territorial Army exam guide',
            ],
        ]);
    }
}
