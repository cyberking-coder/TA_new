<?php

namespace App\Filament\Resources\Newsletters\Schemas;

use App\Models\Newsletter;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class NewsletterForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Audience')
                ->description('Officer and soldier lists are strictly separate. Suppressions are checked again at send time.')
                ->schema([
                    Select::make('audience')
                        ->required()
                        ->options(['officer' => 'Officer subscribers', 'soldier' => 'Soldier subscribers']),
                    Select::make('status')
                        ->disabled()
                        ->default(Newsletter::STATUS_DRAFT)
                        ->options([
                            Newsletter::STATUS_DRAFT => 'Draft',
                            Newsletter::STATUS_SENDING => 'Sending',
                            Newsletter::STATUS_SENT => 'Sent',
                            Newsletter::STATUS_CANCELLED => 'Cancelled',
                        ]),
                ])->columns(2),
            Section::make('Content')
                ->schema([
                    TextInput::make('subject')->required()->maxLength(200),
                    Textarea::make('body_html')
                        ->label('Body (HTML)')
                        ->required()
                        ->rows(12)
                        ->columnSpanFull(),
                ]),
        ]);
    }
}
