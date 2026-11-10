<?php

namespace App\Filament\Resources\Notices\Schemas;

use App\Models\Notice;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class NoticeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Content')
                ->description('Notice body, source attribution and dates. Draft until published.')
                ->schema([
                    TextInput::make('title')
                        ->required()
                        ->maxLength(200)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($state, $set, $get) =>
                            blank($get('slug')) ? $set('slug', Str::slug($state)) : null
                        ),
                    TextInput::make('slug')
                        ->required()
                        ->maxLength(200)
                        ->unique(ignoreRecord: true)
                        ->helperText('URL slug. Change carefully: a slug change will register a redirect in Phase 5.'),
                    Textarea::make('summary')
                        ->required()
                        ->maxLength(500)
                        ->rows(3)
                        ->columnSpanFull(),
                    Textarea::make('body')
                        ->rows(8)
                        ->columnSpanFull(),
                ])
                ->columns(2),

            Section::make('Classification')
                ->schema([
                    Select::make('exam_id')
                        ->label('Exam')
                        ->relationship('exam', 'name')
                        ->searchable()
                        ->preload(),
                    Select::make('type')
                        ->required()
                        ->default('notification')
                        ->options([
                            'notification' => 'Notification',
                            'result' => 'Result',
                            'admit-card' => 'Admit card',
                        ]),
                    DatePicker::make('published_on')
                        ->required()
                        ->label('Dated')
                        ->default(now()),
                ])
                ->columns(3),

            Section::make('Source attribution')
                ->description('Notices that quote or point to an official document must name and link the issuing source.')
                ->schema([
                    TextInput::make('source_label')
                        ->label('Issuing source')
                        ->maxLength(120),
                    TextInput::make('source_url')
                        ->label('Source URL')
                        ->url()
                        ->maxLength(2048),
                    Select::make('pdf_id')
                        ->label('Attached PDF')
                        ->relationship('pdf', 'title')
                        ->searchable()
                        ->preload(),
                ])
                ->columns(3),

            Section::make('Publication')
                ->schema([
                    Select::make('status')
                        ->required()
                        ->default(Notice::STATUS_DRAFT)
                        ->options([
                            Notice::STATUS_DRAFT => 'Draft',
                            Notice::STATUS_PUBLISHED => 'Published',
                            Notice::STATUS_WITHDRAWN => 'Withdrawn',
                        ])
                        ->helperText('Draft stays private. Withdrawn removes from listings, detail page and sitemap.'),
                ]),
        ]);
    }
}
