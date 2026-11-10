<?php

namespace App\Filament\Resources\Pdfs\Pages;

use App\Filament\Resources\Pdfs\PdfResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePdf extends CreateRecord
{
    protected static string $resource = PdfResource::class;
}
