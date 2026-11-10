<?php

namespace App\Http\Controllers;

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

        $all = SyntheticFixtures::notices();
        $filtered = array_values(array_filter($all, function ($n) use ($filters) {
            if ($filters['exam'] && $n['exam'] !== $filters['exam']) return false;
            if ($filters['type'] && $n['type'] !== $filters['type']) return false;
            if ($filters['year'] && !str_starts_with($n['date'], (string) $filters['year'])) return false;
            return true;
        }));
        usort($filtered, fn ($a, $b) => strcmp($b['date'], $a['date']));

        $years = collect($all)->pluck('date')->map(fn ($d) => substr($d, 0, 4))->unique()->sort()->reverse()->values()->all();

        return view('notices.index', [
            'title' => 'Notices and downloads — Territorial Army exam guide',
            'description' => 'Dated notices, results and admit-card updates for Territorial Army exams. Filter by exam, year and type.',
            'canonical' => url()->current(),
            'notices' => $filtered,
            'exams' => SyntheticFixtures::exams(),
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
        $notice = SyntheticFixtures::findNotice($slug);
        abort_unless($notice, 404);

        return view('notices.show', [
            'title' => $notice['title'].' — Territorial Army exam guide',
            'description' => $notice['summary'],
            'canonical' => url()->current(),
            'notice' => $notice,
            'jsonLd' => [
                '@context' => 'https://schema.org',
                '@type' => 'NewsArticle',
                'headline' => $notice['title'],
                'datePublished' => $notice['date'],
                'author' => ['@type' => 'Organization', 'name' => 'Territorial Army exam guide'],
            ],
        ]);
    }
}
