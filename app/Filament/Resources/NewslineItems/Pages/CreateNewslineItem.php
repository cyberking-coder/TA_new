<?php

namespace App\Filament\Resources\NewslineItems\Pages;

use App\Filament\Resources\NewslineItems\NewslineItemResource;
use Filament\Resources\Pages\CreateRecord;

class CreateNewslineItem extends CreateRecord
{
    protected static string $resource = NewslineItemResource::class;
}
