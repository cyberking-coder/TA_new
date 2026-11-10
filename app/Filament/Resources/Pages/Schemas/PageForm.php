<?php

namespace App\Filament\Resources\Pages\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('slug')
                    ->required(),
                Select::make('parent_id')
                    ->relationship('parent', 'title'),
                TextInput::make('menu_label'),
                TextInput::make('title')
                    ->required(),
                Textarea::make('body')
                    ->columnSpanFull(),
                TextInput::make('seo_title'),
                TextInput::make('seo_description'),
                TextInput::make('status')
                    ->required()
                    ->default('draft'),
                DateTimePicker::make('published_at'),
                Select::make('author_id')
                    ->relationship('author', 'name'),
            ]);
    }
}
