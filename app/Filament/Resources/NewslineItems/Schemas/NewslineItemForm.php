<?php

namespace App\Filament\Resources\NewslineItems\Schemas;

use App\Models\NewslineItem;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class NewslineItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->maxLength(200),
                TextInput::make('destination_url')
                    ->label('Destination URL')
                    ->url()
                    ->required()
                    ->maxLength(2048),
                DatePicker::make('published_on')
                    ->required()
                    ->default(now()),
                TextInput::make('display_order')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->default(0)
                    ->helperText('Lower numbers appear first. Ties break by most-recently-published.'),
                Select::make('status')
                    ->required()
                    ->default(NewslineItem::STATUS_DRAFT)
                    ->options([
                        NewslineItem::STATUS_DRAFT     => 'Draft',
                        NewslineItem::STATUS_PUBLISHED => 'Published',
                        NewslineItem::STATUS_WITHDRAWN => 'Withdrawn',
                    ])
                    ->helperText('Only Published items appear in the public homepage ticker.'),
            ]);
    }
}
