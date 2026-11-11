<?php

namespace App\Filament\Resources\HeroMedia\Tables;

use App\Filament\Support\NewBadgeActions;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;

class HeroMediaTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('display_order')
            ->columns([
                ImageColumn::make('image_path')->disk('public')->height(48),
                TextColumn::make('display_order')->label('Order')->sortable(),
                TextColumn::make('duration_seconds')->label('Duration')->suffix(' s')->sortable(),
                IconColumn::make('is_active')->label('Active')->boolean(),
                TextColumn::make('headline')->searchable()->limit(40)->toggleable(),
                TextColumn::make('alt_text')->label('Alt')->limit(30)->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')->dateTime('d M Y H:i')->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('active')->query(fn ($q) => $q->where('is_active', true))->label('Active only'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
