<?php

namespace App\Filament\Resources\NewslineItems;

use App\Filament\Resources\NewslineItems\Pages\CreateNewslineItem;
use App\Filament\Resources\NewslineItems\Pages\EditNewslineItem;
use App\Filament\Resources\NewslineItems\Pages\ListNewslineItems;
use App\Filament\Resources\NewslineItems\Schemas\NewslineItemForm;
use App\Filament\Resources\NewslineItems\Tables\NewslineItemsTable;
use App\Models\NewslineItem;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class NewslineItemResource extends Resource
{
    protected static ?string $model = NewslineItem::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|\UnitEnum|null $navigationGroup = 'Content';
    protected static ?string $navigationLabel = 'Newsline ticker';


    public static function form(Schema $schema): Schema
    {
        return NewslineItemForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return NewslineItemsTable::configure($table);
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
            'index' => ListNewslineItems::route('/'),
            'create' => CreateNewslineItem::route('/create'),
            'edit' => EditNewslineItem::route('/{record}/edit'),
        ];
    }
}
