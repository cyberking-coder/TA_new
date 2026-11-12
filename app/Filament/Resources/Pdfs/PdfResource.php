<?php

namespace App\Filament\Resources\Pdfs;

use App\Filament\Resources\Pdfs\Pages\CreatePdf;
use App\Filament\Resources\Pdfs\Pages\EditPdf;
use App\Filament\Resources\Pdfs\Pages\ListPdfs;
use App\Filament\Resources\Pdfs\Schemas\PdfForm;
use App\Filament\Resources\Pdfs\Tables\PdfsTable;
use App\Models\Pdf;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PdfResource extends Resource
{
    protected static ?string $model = Pdf::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|\UnitEnum|null $navigationGroup = 'Content';
    protected static ?string $navigationLabel = 'PDFs';


    public static function form(Schema $schema): Schema
    {
        return PdfForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PdfsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPdfs::route('/'),
            'create' => CreatePdf::route('/create'),
            'edit' => EditPdf::route('/{record}/edit'),
        ];
    }
}
