<?php

namespace App\Filament\Resources\OfficerSubscribers\Tables;

use App\Filament\Support\SubscriberTableBuilder;
use Filament\Tables\Table;

class OfficerSubscribersTable
{
    public static function configure(Table $table): Table
    {
        return SubscriberTableBuilder::configure($table, 'officer');
    }
}
