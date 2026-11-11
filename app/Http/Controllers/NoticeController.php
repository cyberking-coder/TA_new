<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Notice;
use App\Support\SyntheticFixtures;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
    public function index(Request $request)
    {
        $filters = [
            'exam' => $request->query('exam'),
            'year' => $request->query('year'),
            'type' => $request->query('type'),
        ];

        $q = Notice::query()->published()->with('exam');

        if ($filters['exam']) {
            $q->whereHas('exam', fn ($e) => $e->where('slug', $filters['exam']));
        }
        if ($filters['type']) {
            $q->where('type', $filters['type']);
        }
        if ($filters['year']) {
            $q->whereYear('published_on', (int) $filters['year']);
        }

        $notices = $q->orderByDesc('published_on')->get()->map(fn ($n) => [
            'slug' => $n->slug,
            'title' => $n->title,
            'summary' => $n->summary,
            'type' => $n->type,
            'date' => $n->published_on->toDateString(),
            'new_until' => $n->new_until,
        ])->all();

        // Fallback to synthetic fixtures until Phase 2 seeded content lands in the DB
        if (empty($notices) && !array_filter($filters)) {
            $notices = SyntheticFixtures::notices();
            usort($notices, fn ($a, $b) => strcmp($b['date'], $a['date']));
        }

        $exams = Exam::query()->orderBy('name')->get(['slug', 'name'])->toArray();
        if (empty($exams)) $exams = SyntheticFixtures::exams();

        $years = Notice::query()->published()->get(['published_on'])
            ->map(fn ($n) => $n->published_on->year)->unique()->sort()->reverse()->values()->all();
        if (empty($years)) {
            $years = collect(SyntheticFixtures::notices())->pluck('date')->map(fn ($d) => (int) substr($d, 0, 4))->unique()->sort()->reverse()->values()->all();
        }

        return view('notices.index', [
            'title' => 'Notices and downloads — Territorial Army exam guide',
            'description' => 'Dated notices, results and admit-card updates for Territorial Army exams. Filter by exam, year and type.',
            'canonical' => url()->current(),
            'notices' => $notices,
            'exams' => $exams,
            'years' => $years,
            'filters' => $filters,
            'jsonLd' => [
                '@context' => 'https://schema.org',
                '@type' => 'BreadcrumbList',
                'itemListElement' => [
                    ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')],
                    ['@type' => 'ListItem', 'position' => 2, 'name' => 'Notices and downloads', 'item' => url()->current()],
                ],
            ],
        ]);
    }

    public function show(string $slug)
    {
        $notice = Notice::query()->with('exam', 'pdf')->where('slug', $slug)->first();

        if ($notice && $notice->status !== Notice::STATUS_PUBLISHED) {
            // Drafts and withdrawn notices must not render for anonymous visitors.
            // Staff preview flow lives behind the Filament panel in Phase 2+.
            abort(404);
        }

        if (!$notice) {
            // Fallback to synthetic fixture only when the slug matches one and nothing in DB.
            $fixture = \App\Support\SyntheticFixtures::findNotice($slug);
            abort_unless($fixture, 404);
            return view('notices.show', [
                'title' => $fixture['title'].' — Territorial Army exam guide',
                'description' => $fixture['summary'],
                'canonical' => url()->current(),
                'notice' => $fixture,
                'jsonLd' => self::articleJsonLd($fixture['title'], $fixture['date']),
            ]);
        }

        $view = [
            'slug' => $notice->slug,
            'title' => $notice->title,
            'summary' => $notice->summary,
            'body' => $notice->body,
            'date' => $notice->published_on->toDateString(),
            'type' => $notice->type,
            'exam_name' => $notice->exam?->name ?? '—',
            'source_label' => $notice->source_label ?? 'Issuing source',
            'source_url' => $notice->source_url ?? '#',
            'source_size' => $notice->pdf?->sizeLabel() ?? '—',
        ];

        return view('notices.show', [
            'title' => $notice->title.' — Territorial Army exam guide',
            'description' => $notice->summary,
            'canonical' => url()->current(),
            'notice' => $view,
            'jsonLd' => self::articleJsonLd($notice->title, $notice->published_on->toDateString()),
        ]);
    }

    private static function articleJsonLd(string $headline, string $date): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'NewsArticle',
            'headline' => $headline,
            'datePublished' => $date,
            'author' => ['@type' => 'Organization', 'name' => 'Territorial Army exam guide'],
        ];
    }
}
