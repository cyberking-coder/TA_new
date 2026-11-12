<?php

namespace App\Filament\Support;

use App\Models\Concerns\HasNewBadge;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Cache;

/**
 * Shared "Mark NEW / Unmark NEW" actions + column for any resource
 * whose model uses HasNewBadge.
 */
class NewBadgeActions
{
    // Keep the window local to this helper too, so it works even when the
    // trait's constant is unreachable through the record instance.
    public const DEFAULT_DAYS = 7;

    public static function column(): TextColumn
    {
        return TextColumn::make('new_until')
            ->label('NEW badge')
            ->badge()
            ->color(fn ($state) => $state && now()->lt($state) ? 'warning' : 'gray')
            ->formatStateUsing(function ($state) {
                if (!$state) return '—';
                $until = \Carbon\Carbon::parse($state);
                if ($until->isPast()) return 'expired '.$until->diffForHumans();
                return 'until '.$until->format('d M Y H:i');
            })
            ->toggleable();
    }

    public static function mark(): Action
    {
        return Action::make('mark_new')
            ->label('Mark NEW')
            ->icon('heroicon-o-sparkles')
            ->color('warning')
            ->visible(fn ($record) => $record && method_exists($record, 'isNew') && !$record->isNew())
            ->requiresConfirmation()
            ->modalDescription('Show a pulsing "NEW" badge next to this item on the public site for the next 7 days.')
            ->action(function ($record) {
                if (!$record || !method_exists($record, 'markAsNew')) return;
                $record->markAsNew(self::DEFAULT_DAYS);
                try { Cache::forget('home.latest_notices'); } catch (\Throwable $e) { /* best-effort */ }
            });
    }

    public static function unmark(): Action
    {
        return Action::make('unmark_new')
            ->label('Clear NEW')
            ->icon('heroicon-o-x-mark')
            ->color('gray')
            ->visible(fn ($record) => $record && method_exists($record, 'isNew') && $record->isNew())
            ->requiresConfirmation()
            ->action(function ($record) {
                if (!$record || !method_exists($record, 'unmarkAsNew')) return;
                $record->unmarkAsNew();
                try { Cache::forget('home.latest_notices'); } catch (\Throwable $e) { /* best-effort */ }
            });
    }
}
