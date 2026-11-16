<?php

namespace App\Filament\Resources\Books\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class BookForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('slug')
                    ->required(),
                TextInput::make('title')
                    ->required(),
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
