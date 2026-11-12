<?php

namespace App\Filament\Resources\ResultRegistrations\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ResultRegistrationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('mobile')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                Select::make('exam_id')
                    ->relationship('exam', 'name')
                    ->required(),
                TextInput::make('roll_number')
                    ->required(),
                TextInput::make('legacy_zone'),
            ]);
    }
}
