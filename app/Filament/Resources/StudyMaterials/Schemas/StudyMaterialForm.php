<?php

namespace App\Filament\Resources\StudyMaterials\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class StudyMaterialForm
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
                    ->unique(table: 'study_materials', column: 'slug', ignoreRecord: true)
                    ->helperText('URL part. Auto-filled from title; keep lowercase letters, numbers, hyphens.'),
                Textarea::make('description')
                    ->columnSpanFull(),
                Select::make('pdf_id')
                    ->relationship('pdf', 'title'),
                Select::make('exam_id')
                    ->relationship('exam', 'name'),
                Select::make('book_edition_id')
                    ->relationship('bookEdition', 'id'),
                TextInput::make('status')
                    ->required()
                    ->default('draft'),
                DateTimePicker::make('published_at'),
            ]);
    }
}
