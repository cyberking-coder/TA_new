<?php

namespace App\Filament\Resources\Books\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class BookForm
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
                    ->unique(table: 'books', column: 'slug', ignoreRecord: true)
                    ->helperText('URL part. Auto-filled from title; keep lowercase letters, numbers, hyphens.'),
                TextInput::make('author'),
                Textarea::make('description')
                    ->columnSpanFull(),
                FileUpload::make('cover_path')
                    ->label('Cover image')
                    ->image()
                    ->disk('public')
                    ->directory('books/covers')
                    ->imagePreviewHeight('180')
                    ->maxSize(5 * 1024)
                    ->helperText('JPG, PNG or WebP. Portrait orientation (approx 2:3) works best. Max 5 MB.'),
                TextInput::make('status')
                    ->required()
                    ->default('draft'),
                DateTimePicker::make('published_at'),
            ]);
    }
}
