<?php

namespace App\Filament\Resources\OfficerSubscribers\Pages;

use App\Filament\Resources\OfficerSubscribers\OfficerSubscriberResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOfficerSubscribers extends ListRecords
{
    protected static string $resource = OfficerSubscriberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
