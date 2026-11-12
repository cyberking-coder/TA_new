<?php

namespace App\Filament\Resources\Orders\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('order_ref')->label('Order #')->searchable()->copyable(),
                TextColumn::make('created_at')->dateTime('d M Y H:i')->label('Placed')->sortable(),
                TextColumn::make('buyer_name')->searchable()->toggleable(),
                TextColumn::make('buyer_mobile')->searchable()->toggleable(),
                TextColumn::make('buyer_email')->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('total_paise')
                    ->label('Total')
                    ->formatStateUsing(fn (int $state) => '₹'.number_format(intdiv($state, 100))),
                TextColumn::make('payment_status')->badge()->color(fn ($s) => match ($s) {
                    'paid' => 'success', 'failed' => 'danger', 'refunded' => 'warning', default => 'gray',
                }),
                TextColumn::make('dispatch_status')->badge()->toggleable(),
                TextColumn::make('paid_at')->dateTime('d M Y')->toggleable(),
            ])
            ->filters([
                SelectFilter::make('payment_status')->options([
                    'pending' => 'Pending', 'paid' => 'Paid', 'failed' => 'Failed', 'refunded' => 'Refunded',
                ]),
                SelectFilter::make('dispatch_status')->options([
                    'unshipped' => 'Unshipped', 'shipped' => 'Shipped', 'delivered' => 'Delivered',
                ]),
            ])
            ->recordActions([ViewAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
