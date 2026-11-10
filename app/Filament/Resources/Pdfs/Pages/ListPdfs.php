<?php

namespace App\Filament\Resources\Pdfs\Pages;

use App\Filament\Resources\Pdfs\PdfResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPdfs extends ListRecords
{
    protected static string $resource = PdfResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
