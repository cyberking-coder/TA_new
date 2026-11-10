<?php

namespace App\Filament\Resources\Reviews\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ReviewForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('book_id')
                    ->relationship('book', 'title')
                    ->required(),
                TextInput::make('author_name')
                    ->required(),
                TextInput::make('rating')
                    ->required()
                    ->numeric(),
                Textarea::make('body')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('moderation_state')
                    ->required()
                    ->default('pending'),
                TextInput::make('moderated_by')
                    ->numeric(),
                DateTimePicker::make('moderated_at'),
            ]);
    }
}
