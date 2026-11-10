<?php

namespace App\Filament\Resources\Pdfs\Schemas;

use App\Models\Pdf;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PdfForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('title')
                ->required()
                ->maxLength(200),
            TextInput::make('download_label')
                ->maxLength(120)
                ->helperText('Shown on the public page link, e.g. "Open source document".'),
            Select::make('disk')
                ->label('Access')
                ->required()
                ->default(Pdf::DISK_PUBLIC)
                ->options([
                    Pdf::DISK_PUBLIC => 'Public — anyone can download',
                    Pdf::DISK_PRIVATE => 'Private — buyer-only (study material)',
                ])
                ->live()
                ->helperText('Private PDFs are served only via signed short-lived links to verified buyers.'),
            FileUpload::make('path')
                ->label('File')
                ->required()
                ->disk(fn ($get) => $get('disk') === Pdf::DISK_PRIVATE ? 'private' : 'public')
                ->directory(fn ($get) => $get('disk') === Pdf::DISK_PRIVATE ? 'study' : 'public-pdfs')
                ->visibility(fn ($get) => $get('disk') === Pdf::DISK_PRIVATE ? 'private' : 'public')
                ->acceptedFileTypes(['application/pdf'])
                ->maxSize(25 * 1024)
                ->afterStateUpdated(function ($state, $set, $get) {
                    // Record size for the public size label
                    if ($state && method_exists($state, 'getSize')) {
                        $set('size_bytes', $state->getSize());
                    }
                }),
            TextInput::make('size_bytes')
                ->numeric()
                ->default(0)
                ->hidden(),
        ]);
    }
}
