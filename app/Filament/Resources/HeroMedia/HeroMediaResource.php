<?php

namespace App\Filament\Resources\HeroMedia;

use App\Filament\Resources\HeroMedia\Pages\CreateHeroMedia;
use App\Filament\Resources\HeroMedia\Pages\EditHeroMedia;
use App\Filament\Resources\HeroMedia\Pages\ListHeroMedia;
use App\Filament\Resources\HeroMedia\Schemas\HeroMediaForm;
use App\Filament\Resources\HeroMedia\Tables\HeroMediaTable;
use App\Models\HeroMedia;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class HeroMediaResource extends Resource
{
    protected static ?string $model = HeroMedia::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|\UnitEnum|null $navigationGroup = 'Content';
    protected static ?string $navigationLabel = 'Hero / slider';


    public static function form(Schema $schema): Schema
    {
        return HeroMediaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HeroMediaTable::configure($table);
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
            'index' => ListHeroMedia::route('/'),
            'create' => CreateHeroMedia::route('/create'),
            'edit' => EditHeroMedia::route('/{record}/edit'),
        ];
    }
}
