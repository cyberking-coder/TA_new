<?php

namespace App\Http\Controllers;

use App\Models\Book;
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
                ->get(['slug', 'title', 'summary', 'published_on'])
                ->map(fn ($n) => [
                    'slug' => $n->slug,
                    'title' => $n->title,
                    'summary' => $n->summary,
                    'date' => $n->published_on->toDateString(),
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

        return view('home.index', [
            'title' => 'Territorial Army exam guide — notices, preparation and the book',
            'description' => 'Dated recruitment notices, preparation resources, and the Territorial Army Commission examination book. Independent publisher, source-attributed notices, buyer-only study access.',
            'canonical' => url('/'),
            'latestNotices' => $latest,
            'featuredBook' => $featuredBook,
            'jsonLd' => [
                '@context' => 'https://schema.org',
                '@type' => 'WebSite',
                'url' => url('/'),
                'name' => 'Territorial Army exam guide',
            ],
        ]);
    }
}
