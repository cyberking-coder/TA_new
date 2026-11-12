<?php

namespace App\Http\Controllers;

use App\Models\Page;

class PagesController extends Controller
{
    public function show(string $slug)
    {
        $page = Page::query()->published()->where('slug', $slug)->first();
        abort_unless($page, 404);

        return view('pages.show', [
            'title' => ($page->seo_title ?: $page->title).' — Territorial Army exam guide',
            'description' => $page->seo_description ?: \Str::limit(strip_tags($page->body ?? ''), 160),
            'canonical' => url()->current(),
            'page' => $page,
            'jsonLd' => [
                '@context' => 'https://schema.org',
                '@type' => 'BreadcrumbList',
                'itemListElement' => [
                    ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')],
                    ['@type' => 'ListItem', 'position' => 2, 'name' => $page->title, 'item' => url()->current()],
                ],
            ],
        ]);
    }
}
