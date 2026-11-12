<?php

namespace App\Filament\Resources\OfficerSubscribers;

use App\Filament\Resources\OfficerSubscribers\Pages\CreateOfficerSubscriber;
use App\Filament\Resources\OfficerSubscribers\Pages\EditOfficerSubscriber;
use App\Filament\Resources\OfficerSubscribers\Pages\ListOfficerSubscribers;
use App\Filament\Resources\OfficerSubscribers\Schemas\OfficerSubscriberForm;
use App\Filament\Resources\OfficerSubscribers\Tables\OfficerSubscribersTable;
use App\Models\OfficerSubscriber;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class OfficerSubscriberResource extends Resource
{
    protected static ?string $model = OfficerSubscriber::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|\UnitEnum|null $navigationGroup = 'People & Exams';
    protected static ?string $navigationLabel = 'Officer subscribers';


    public static function form(Schema $schema): Schema
    {
        return OfficerSubscriberForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OfficerSubscribersTable::configure($table);
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
            'index' => ListOfficerSubscribers::route('/'),
            'create' => CreateOfficerSubscriber::route('/create'),
            'edit' => EditOfficerSubscriber::route('/{record}/edit'),
        ];
    }
}
