<?php

namespace App\Filament\Resources\HeroMedia\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class HeroMediaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('image_path')
                    ->image(),
                TextInput::make('headline')
                    ->required(),
                TextInput::make('subhead'),
                TextInput::make('action_label'),
                TextInput::make('action_url')
                    ->url(),
                Toggle::make('is_featured')
                    ->required(),
            ]);
    }
}
