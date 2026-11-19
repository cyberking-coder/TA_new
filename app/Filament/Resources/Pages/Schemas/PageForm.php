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
                    ->maxLength(200)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, $get, $set) {
                        if (blank($get('slug'))) {
                            $set('slug', Str::slug((string) $state));
                        }
                        if (blank($get('seo_title'))) {
                            $set('seo_title', (string) $state);
                        }
                    }),
                TextInput::make('slug')
                    ->required()
                    ->maxLength(200)
                    ->unique(table: 'pages', column: 'slug', ignoreRecord: true)
                    ->helperText('URL part. Auto-filled from title; keep lowercase letters, numbers, hyphens.'),
                Select::make('parent_id')
                    ->relationship('parent', 'title'),
                TextInput::make('menu_label')
                    ->maxLength(80)
                    ->helperText('Optional. Shown in nav menus instead of the full title.'),
                Textarea::make('body')
                    ->required()
                    ->rows(10)
                    ->columnSpanFull()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, $get, $set) {
                        if (blank($get('seo_description'))) {
                            $plain = trim(preg_replace('/\s+/', ' ', strip_tags((string) $state)));
                            $set('seo_description', Str::limit($plain, 155, ''));
                        }
                    }),
                TextInput::make('seo_title')
                    ->maxLength(70)
                    ->helperText('Auto-filled from title. Max 70 chars for Google.'),
                TextInput::make('seo_description')
                    ->maxLength(160)
                    ->helperText('Auto-filled from the body. Max 160 chars; shown under the title in Google results.'),
                TextInput::make('status')
                    ->required()
                    ->default('draft'),
                DateTimePicker::make('published_at'),
                Select::make('author_id')
                    ->relationship('author', 'name'),
            ]);
    }
}
