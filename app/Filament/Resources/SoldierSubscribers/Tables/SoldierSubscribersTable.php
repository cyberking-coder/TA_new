<?php

namespace App\Filament\Resources\SoldierSubscribers\Tables;

use App\Filament\Support\SubscriberTableBuilder;
use Filament\Tables\Table;

class SoldierSubscribersTable
{
    public static function configure(Table $table): Table
    {
        return SubscriberTableBuilder::configure($table, 'soldier');
    }
}
