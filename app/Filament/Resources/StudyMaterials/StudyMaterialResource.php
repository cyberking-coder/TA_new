<?php

namespace App\Filament\Resources\StudyMaterials;

use App\Filament\Resources\StudyMaterials\Pages\CreateStudyMaterial;
use App\Filament\Resources\StudyMaterials\Pages\EditStudyMaterial;
use App\Filament\Resources\StudyMaterials\Pages\ListStudyMaterials;
use App\Filament\Resources\StudyMaterials\Schemas\StudyMaterialForm;
use App\Filament\Resources\StudyMaterials\Tables\StudyMaterialsTable;
use App\Models\StudyMaterial;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class StudyMaterialResource extends Resource
{
    protected static ?string $model = StudyMaterial::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|\UnitEnum|null $navigationGroup = 'Content';
    protected static ?string $navigationLabel = 'Study material';


    public static function form(Schema $schema): Schema
    {
        return StudyMaterialForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StudyMaterialsTable::configure($table);
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
            'index' => ListStudyMaterials::route('/'),
            'create' => CreateStudyMaterial::route('/create'),
            'edit' => EditStudyMaterial::route('/{record}/edit'),
        ];
    }
}
