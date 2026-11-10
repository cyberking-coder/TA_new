<?php

namespace App\Filament\Support;

use App\Models\ExportLog;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SubscriberTableBuilder
{
    public static function configure(Table $table, string $audience): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('email')->searchable()->copyable(),
                TextColumn::make('name')->searchable()->toggleable(),
                TextColumn::make('mobile')->toggleable()->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('verified_at')->label('Verified')->boolean()
                    ->getStateUsing(fn ($r) => (bool) $r->verified_at),
                IconColumn::make('unsubscribed_at')->label('Unsubscribed')->boolean()
                    ->getStateUsing(fn ($r) => (bool) $r->unsubscribed_at),
                IconColumn::make('suppressed_at')->label('Suppressed')->boolean()
                    ->getStateUsing(fn ($r) => (bool) $r->suppressed_at),
                TextColumn::make('consent_at')->dateTime('d M Y')->toggleable(),
                TextColumn::make('created_at')->dateTime('d M Y')->sortable()->toggleable(),
            ])
            ->filters([
                Filter::make('verified')->query(fn ($q) => $q->whereNotNull('verified_at'))->label('Verified only'),
                Filter::make('unverified')->query(fn ($q) => $q->whereNull('verified_at'))->label('Not yet verified'),
                Filter::make('unsubscribed')->query(fn ($q) => $q->whereNotNull('unsubscribed_at'))->label('Unsubscribed'),
                Filter::make('eligible')
                    ->query(fn ($q) => $q->whereNotNull('verified_at')->whereNull('unsubscribed_at')->whereNull('suppressed_at'))
                    ->label('Eligible for send'),
            ])
            ->recordActions([EditAction::make()])
            ->headerActions([
                Action::make('export')
                    ->label('Export CSV')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->visible(fn () => auth()->user()?->hasRole(User::ROLE_ADMIN, User::ROLE_OPS))
                    ->action(function ($livewire) use ($audience) {
                        $rows = $livewire->getFilteredTableQuery()->get();
                        $filename = $audience.'-subscribers-'.now()->format('Ymd-His').'.csv';
                        ExportLog::create([
                            'actor_id' => auth()->id(),
                            'module' => $audience.'_subscribers',
                            'filters_json' => $livewire->tableFilters ?? [],
                            'row_count' => $rows->count(),
                        ]);
                        return new StreamedResponse(function () use ($rows) {
                            $out = fopen('php://output', 'w');
                            fputcsv($out, ['Email', 'Name', 'Mobile', 'Verified at', 'Unsubscribed at', 'Suppressed at', 'Consent at', 'Created at']);
                            foreach ($rows as $r) {
                                fputcsv($out, [
                                    self::safe($r->email), self::safe($r->name), self::safe($r->mobile),
                                    optional($r->verified_at)->toDateTimeString(),
                                    optional($r->unsubscribed_at)->toDateTimeString(),
                                    optional($r->suppressed_at)->toDateTimeString(),
                                    optional($r->consent_at)->toDateTimeString(),
                                    optional($r->created_at)->toDateTimeString(),
                                ]);
                            }
                            fclose($out);
                        }, 200, [
                            'Content-Type' => 'text/csv; charset=UTF-8',
                            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
                        ]);
                    }),
            ])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    private static function safe(?string $v): string
    {
        $v = (string) $v;
        if ($v !== '' && str_contains('=+-@', $v[0])) return "'".$v;
        return $v;
    }
}
