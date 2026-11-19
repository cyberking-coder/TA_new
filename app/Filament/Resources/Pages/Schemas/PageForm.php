<?php

namespace App\Filament\Resources\Pages\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, $get, $set) =>
                        blank($get('slug')) ? $set('slug', Str::slug((string) $state)) : null
                    ),
                TextInput::make('slug')
                    ->required()
                    ->unique(table: 'pages', column: 'slug', ignoreRecord: true)
                    ->helperText('URL part. Auto-filled from title; keep lowercase letters, numbers, hyphens.'),
                Select::make('parent_id')
                    ->relationship('parent', 'title'),
                TextInput::make('menu_label'),
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
