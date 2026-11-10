<?php

namespace App\Filament\Resources\Reviews\Tables;

use App\Models\Review;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ReviewsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('book.title')->label('Book')->toggleable(),
                TextColumn::make('author_name')->label('Reviewer')->searchable(),
                TextColumn::make('rating')->badge()->color(fn (int $state) => $state >= 4 ? 'success' : ($state >= 3 ? 'warning' : 'danger')),
                TextColumn::make('body')->wrap()->limit(80)->label('Excerpt'),
                TextColumn::make('moderation_state')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('moderator.name')->label('Moderator')->toggleable(),
                TextColumn::make('moderated_at')->dateTime('d M Y H:i')->toggleable(),
            ])
            ->filters([
                SelectFilter::make('moderation_state')->options([
                    'pending' => 'Pending',
                    'approved' => 'Approved',
                    'rejected' => 'Rejected',
                ]),
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Review $r) => $r->moderation_state !== Review::STATE_APPROVED)
                    ->requiresConfirmation()
                    ->action(fn (Review $r) => $r->update([
                        'moderation_state' => Review::STATE_APPROVED,
                        'moderated_by' => auth()->id(),
                        'moderated_at' => now(),
                    ])),
                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (Review $r) => $r->moderation_state !== Review::STATE_REJECTED)
                    ->requiresConfirmation()
                    ->action(fn (Review $r) => $r->update([
                        'moderation_state' => Review::STATE_REJECTED,
                        'moderated_by' => auth()->id(),
                        'moderated_at' => now(),
                    ])),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
