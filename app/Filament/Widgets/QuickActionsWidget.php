<?php

namespace App\Filament\Widgets;

use App\Models\Notice;
use App\Models\Page;
use App\Models\Pdf;
use App\Models\ResultRegistration;
use App\Models\Review;
use Filament\Widgets\Widget;

class QuickActionsWidget extends Widget
{
    protected string $view = 'filament.widgets.quick-actions';
    protected static ?int $sort = 2;
    protected int|string|array $columnSpan = 'full';

    protected function getViewData(): array
    {
        return [
            'actions' => [
                [
                    'label'  => 'Publish a notice',
                    'desc'   => 'Create a new notice visible on the public notice board.',
                    'icon'   => 'heroicon-o-megaphone',
                    'url'    => url('/admin/notices/create'),
                    'badge'  => Notice::query()->where('status', 'draft')->count().' draft',
                ],
                [
                    'label'  => 'Upload a PDF',
                    'desc'   => 'Add a public download or a buyer-only study PDF.',
                    'icon'   => 'heroicon-o-document-arrow-up',
                    'url'    => url('/admin/pdfs/create'),
                    'badge'  => Pdf::query()->count().' total',
                ],
                [
                    'label'  => 'Review new submissions',
                    'desc'   => 'Moderate recently-submitted book reviews.',
                    'icon'   => 'heroicon-o-star',
                    'url'    => url('/admin/reviews'),
                    'badge'  => Review::query()->where('status', 'pending')->count().' pending',
                ],
                [
                    'label'  => 'Edit a page',
                    'desc'   => 'Content for the public site — policies, info pages, PDFs.',
                    'icon'   => 'heroicon-o-document-text',
                    'url'    => url('/admin/pages'),
                    'badge'  => Page::query()->count().' total',
                ],
                [
                    'label'  => 'Export result registrations',
                    'desc'   => 'Candidates registered for TA Office result notifications.',
                    'icon'   => 'heroicon-o-academic-cap',
                    'url'    => url('/admin/result-registrations'),
                    'badge'  => ResultRegistration::query()->count().' rows',
                ],
                [
                    'label'  => 'Site settings',
                    'desc'   => 'Flip OTP and the result-notification page ON / OFF.',
                    'icon'   => 'heroicon-o-cog-6-tooth',
                    'url'    => url('/admin/settings'),
                    'badge'  => null,
                ],
            ],
        ];
    }
}
