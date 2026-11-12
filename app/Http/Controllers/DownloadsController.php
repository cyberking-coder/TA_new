<?php

namespace App\Http\Controllers;

use App\Models\Pdf;

class DownloadsController extends Controller
{
    public function index()
    {
        $pdfs = Pdf::query()
            ->where('disk', Pdf::DISK_PUBLIC)
            ->orderByDesc('updated_at')
            ->get(['id', 'title', 'download_label', 'size_bytes', 'updated_at']);

        return view('downloads.index', [
            'title' => 'Important downloads — Territorial Army exam guide',
            'description' => 'Publicly available PDFs: recruitment notifications, syllabi, past notices and official documents.',
            'canonical' => url()->current(),
            'pdfs' => $pdfs,
            'jsonLd' => [
                '@context' => 'https://schema.org',
                '@type' => 'BreadcrumbList',
                'itemListElement' => [
                    ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')],
                    ['@type' => 'ListItem', 'position' => 2, 'name' => 'Important downloads', 'item' => url()->current()],
                ],
            ],
        ]);
    }
}
