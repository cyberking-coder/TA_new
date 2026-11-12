<?php

namespace App\Filament\Resources\ResultRegistrations;

use App\Filament\Resources\ResultRegistrations\Pages\CreateResultRegistration;
use App\Filament\Resources\ResultRegistrations\Pages\EditResultRegistration;
use App\Filament\Resources\ResultRegistrations\Pages\ListResultRegistrations;
use App\Filament\Resources\ResultRegistrations\Schemas\ResultRegistrationForm;
use App\Filament\Resources\ResultRegistrations\Tables\ResultRegistrationsTable;
use App\Models\ResultRegistration;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ResultRegistrationResource extends Resource
{
    protected static ?string $model = ResultRegistration::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|\UnitEnum|null $navigationGroup = 'People & Exams';
    protected static ?string $navigationLabel = 'Result registration';


    public static function form(Schema $schema): Schema
    {
        return ResultRegistrationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ResultRegistrationsTable::configure($table);
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
            'index' => ListResultRegistrations::route('/'),
            'create' => CreateResultRegistration::route('/create'),
            'edit' => EditResultRegistration::route('/{record}/edit'),
        ];
    }
}
