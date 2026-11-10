<?php

namespace App\Filament\Resources\ResultRegistrations\Pages;

use App\Filament\Resources\ResultRegistrations\ResultRegistrationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditResultRegistration extends EditRecord
{
    protected static string $resource = ResultRegistrationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
