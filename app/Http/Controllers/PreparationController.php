<?php

namespace App\Http\Controllers;

use App\Models\Pdf;

class PreparationController extends Controller
{
    public function previousPapers()
    {
        return $this->renderPapers(
            Pdf::CATEGORY_PREVIOUS_PAPERS,
            'Previous solved question papers',
            'Past-year question papers for Territorial Army exams, with solutions and keyed answers where available.',
            'previous',
        );
    }

    public function practicePapers()
    {
        return $this->renderPapers(
            Pdf::CATEGORY_PRACTICE_PAPERS,
            'Practice papers',
            'Original practice sets to rehearse the exam format and timing. Not past papers — distinct from previous solved papers.',
            'practice',
        );
    }

    private function renderPapers(string $category, string $heading, string $blurb, string $navKey)
    {
        $pdfs = Pdf::query()
            ->where('disk', Pdf::DISK_PUBLIC)
            ->where('category', $category)
            ->orderByDesc('updated_at')
            ->get(['id', 'title', 'download_label', 'size_bytes', 'updated_at', 'new_until']);

        return view('preparation.papers', [
            'title' => $heading.' — Territorial Army exam guide',
            'description' => $blurb,
            'canonical' => url()->current(),
            'nav' => $navKey,
            'heading' => $heading,
            'blurb' => $blurb,
            'pdfs' => $pdfs,
            'jsonLd' => [
                '@context' => 'https://schema.org',
                '@type' => 'BreadcrumbList',
                'itemListElement' => [
                    ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')],
                    ['@type' => 'ListItem', 'position' => 2, 'name' => $heading, 'item' => url()->current()],
                ],
            ],
        ]);
    }
}
