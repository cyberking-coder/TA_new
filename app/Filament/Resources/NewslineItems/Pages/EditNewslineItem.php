<?php

namespace App\Filament\Resources\NewslineItems\Pages;

use App\Filament\Resources\NewslineItems\NewslineItemResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditNewslineItem extends EditRecord
{
    protected static string $resource = NewslineItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
