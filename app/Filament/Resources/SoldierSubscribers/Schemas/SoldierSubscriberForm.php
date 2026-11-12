<?php

namespace App\Filament\Resources\SoldierSubscribers\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SoldierSubscriberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('name'),
                TextInput::make('mobile'),
                DateTimePicker::make('consent_at'),
                DateTimePicker::make('verified_at'),
                DateTimePicker::make('unsubscribed_at'),
                DateTimePicker::make('suppressed_at'),
                TextInput::make('suppression_reason'),
            ]);
    }
}
