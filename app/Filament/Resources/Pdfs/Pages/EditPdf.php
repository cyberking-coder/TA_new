<?php

namespace App\Filament\Resources\Pdfs\Pages;

use App\Filament\Resources\Pdfs\PdfResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPdf extends EditRecord
{
    protected static string $resource = PdfResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
