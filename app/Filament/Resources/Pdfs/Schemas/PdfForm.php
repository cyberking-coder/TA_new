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
                ->helperText('Private PDFs are served only via signed short-lived links to verified buyers.'),
            // Upload always lands on the "public" filesystem disk. The `disk` column
            // above is a POLICY marker, not a storage selector: PdfDownloadController
            // refuses a public link to a row with disk=private, and StudyController
            // enforces buyer entitlement + signed URL before streaming the bytes.
            // This keeps the upload surface simple and avoids the "Path must not be
            // empty" error that comes from evaluating closures during form boot.
            FileUpload::make('path')
                ->label('File')
                ->required()
                ->disk('public')
                ->directory('pdfs')
                ->acceptedFileTypes(['application/pdf'])
                ->maxSize(25 * 1024)
                ->downloadable()
                ->previewable(false)
                ->afterStateUpdated(function ($state, $set) {
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
