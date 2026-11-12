<?php

namespace App\Filament\Resources\ResultRegistrations\Pages;

use App\Filament\Resources\ResultRegistrations\ResultRegistrationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListResultRegistrations extends ListRecords
{
    protected static string $resource = ResultRegistrationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
