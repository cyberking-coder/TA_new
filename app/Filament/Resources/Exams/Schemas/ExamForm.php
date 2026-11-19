<?php

namespace App\Filament\Resources\Exams\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ExamForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, $get, $set) =>
                        blank($get('slug')) ? $set('slug', Str::slug((string) $state)) : null
                    ),
                TextInput::make('slug')
                    ->required()
                    ->unique(table: 'exams', column: 'slug', ignoreRecord: true)
                    ->helperText('URL part. Auto-filled from name; keep lowercase letters, numbers, hyphens.'),
                TextInput::make('short_code'),
            ]);
    }
}
