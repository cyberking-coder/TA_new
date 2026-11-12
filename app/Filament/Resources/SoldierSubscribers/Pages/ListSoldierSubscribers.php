<?php

namespace App\Filament\Resources\SoldierSubscribers\Pages;

use App\Filament\Resources\SoldierSubscribers\SoldierSubscriberResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSoldierSubscribers extends ListRecords
{
    protected static string $resource = SoldierSubscriberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
