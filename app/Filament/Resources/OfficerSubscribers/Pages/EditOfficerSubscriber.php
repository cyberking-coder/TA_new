<?php

namespace App\Filament\Resources\OfficerSubscribers\Pages;

use App\Filament\Resources\OfficerSubscribers\OfficerSubscriberResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditOfficerSubscriber extends EditRecord
{
    protected static string $resource = OfficerSubscriberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
