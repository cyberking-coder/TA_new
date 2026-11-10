<?php

namespace App\Filament\Resources\Notices\Tables;

use App\Models\Notice;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Cache;

class NoticesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('published_on', 'desc')
            ->columns([
                TextColumn::make('published_on')
                    ->date('d M Y')
                    ->label('Dated')
                    ->sortable(),
                TextColumn::make('title')
                    ->searchable()
                    ->wrap()
                    ->limit(60),
                TextColumn::make('exam.name')
                    ->label('Exam')
                    ->toggleable(),
                TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'result' => 'success',
                        'admit-card' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'published' => 'success',
                        'withdrawn' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('source_label')
                    ->label('Source')
                    ->toggleable()
                    ->limit(30),
                TextColumn::make('author.name')
                    ->label('Author')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    'draft' => 'Draft',
                    'published' => 'Published',
                    'withdrawn' => 'Withdrawn',
                ]),
                SelectFilter::make('type')->options([
                    'notification' => 'Notification',
                    'result' => 'Result',
                    'admit-card' => 'Admit card',
                ]),
                SelectFilter::make('exam')->relationship('exam', 'name'),
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('publish')
                    ->label('Publish')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Notice $r) => $r->status !== Notice::STATUS_PUBLISHED)
                    ->requiresConfirmation()
                    ->action(function (Notice $r) {
                        $r->update([
                            'status' => Notice::STATUS_PUBLISHED,
                            'published_at' => $r->published_at ?? now(),
                            'author_id' => $r->author_id ?? auth()->id(),
                        ]);
                        Cache::forget('home.latest_notices');
                    }),
                Action::make('withdraw')
                    ->label('Withdraw')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (Notice $r) => $r->status === Notice::STATUS_PUBLISHED)
                    ->requiresConfirmation()
                    ->action(function (Notice $r) {
                        $r->update(['status' => Notice::STATUS_WITHDRAWN]);
                        Cache::forget('home.latest_notices');
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
