<?php

namespace App\Filament\Resources\Pdfs\Tables;

use App\Filament\Support\NewBadgeActions;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PdfsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('disk')
                    ->searchable(),
                TextColumn::make('path')
                    ->searchable(),
                TextColumn::make('size_bytes')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('mime')
                    ->searchable(),
                TextColumn::make('download_label')
                    ->searchable(),
                TextColumn::make('uploaded_by')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                NewBadgeActions::mark(),
                NewBadgeActions::unmark(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
