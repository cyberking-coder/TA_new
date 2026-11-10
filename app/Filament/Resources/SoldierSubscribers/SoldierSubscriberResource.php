<?php

namespace App\Filament\Resources\SoldierSubscribers;

use App\Filament\Resources\SoldierSubscribers\Pages\CreateSoldierSubscriber;
use App\Filament\Resources\SoldierSubscribers\Pages\EditSoldierSubscriber;
use App\Filament\Resources\SoldierSubscribers\Pages\ListSoldierSubscribers;
use App\Filament\Resources\SoldierSubscribers\Schemas\SoldierSubscriberForm;
use App\Filament\Resources\SoldierSubscribers\Tables\SoldierSubscribersTable;
use App\Models\SoldierSubscriber;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SoldierSubscriberResource extends Resource
{
    protected static ?string $model = SoldierSubscriber::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|\UnitEnum|null $navigationGroup = 'People & Exams';
    protected static ?string $navigationLabel = 'Soldier subscribers';


    public static function form(Schema $schema): Schema
    {
        return SoldierSubscriberForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SoldierSubscribersTable::configure($table);
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
            'index' => ListSoldierSubscribers::route('/'),
            'create' => CreateSoldierSubscriber::route('/create'),
            'edit' => EditSoldierSubscriber::route('/{record}/edit'),
        ];
    }
}
