<?php

namespace App\Filament\Resources\NewslineItems\Pages;

use App\Filament\Resources\NewslineItems\NewslineItemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListNewslineItems extends ListRecords
{
    protected static string $resource = NewslineItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
