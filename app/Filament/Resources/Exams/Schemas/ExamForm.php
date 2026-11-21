<?php

namespace App\Filament\Resources\Exams\Schemas;

use App\Models\Exam;
use Filament\Forms\Components\Select;
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
                    ->maxLength(120)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, $get, $set) =>
                        blank($get('slug')) ? $set('slug', Str::slug((string) $state)) : null
                    ),
                TextInput::make('slug')
                    ->required()
                    ->maxLength(140)
                    ->unique(table: 'exams', column: 'slug', ignoreRecord: true)
                    ->helperText('URL part. Auto-filled from name.'),
                TextInput::make('short_code')
                    ->maxLength(20)
                    ->helperText('Optional abbreviation shown next to the name on the public form.'),
                Select::make('status')
                    ->required()
                    ->default(Exam::STATUS_DRAFT)
                    ->options([
                        Exam::STATUS_DRAFT       => 'Draft (hidden from public form)',
                        Exam::STATUS_PUBLISHED   => 'Published (visible on result registration)',
                        Exam::STATUS_UNPUBLISHED => 'Unpublished (hidden, data kept)',
                    ])
                    ->helperText('Only Published examinations appear on the public result-registration dropdown.'),
            ]);
    }
}
