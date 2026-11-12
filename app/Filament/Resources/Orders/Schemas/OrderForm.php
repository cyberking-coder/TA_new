<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('order_ref')
                    ->required(),
                TextInput::make('buyer_name')
                    ->required(),
                TextInput::make('buyer_email')
                    ->email()
                    ->required(),
                TextInput::make('buyer_mobile')
                    ->required(),
                Textarea::make('shipping_json')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('payment_status')
                    ->required()
                    ->default('pending'),
                TextInput::make('dispatch_status')
                    ->required()
                    ->default('unshipped'),
                TextInput::make('subtotal_paise')
                    ->required()
                    ->numeric(),
                TextInput::make('discount_paise')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('shipping_paise')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('total_paise')
                    ->required()
                    ->numeric(),
                TextInput::make('currency')
                    ->required()
                    ->default('INR'),
                DateTimePicker::make('placed_at'),
                DateTimePicker::make('paid_at'),
                Select::make('buyer_id')
                    ->relationship('buyer', 'id'),
            ]);
    }
}
