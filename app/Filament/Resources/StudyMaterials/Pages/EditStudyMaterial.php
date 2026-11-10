<?php

namespace App\Filament\Resources\StudyMaterials\Pages;

use App\Filament\Resources\StudyMaterials\StudyMaterialResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditStudyMaterial extends EditRecord
{
    protected static string $resource = StudyMaterialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
