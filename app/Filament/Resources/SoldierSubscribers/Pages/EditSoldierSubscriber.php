<?php

namespace App\Filament\Resources\SoldierSubscribers\Pages;

use App\Filament\Resources\SoldierSubscribers\SoldierSubscriberResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSoldierSubscriber extends EditRecord
{
    protected static string $resource = SoldierSubscriberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
