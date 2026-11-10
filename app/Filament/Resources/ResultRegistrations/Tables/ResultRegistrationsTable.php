<?php

namespace App\Filament\Resources\ResultRegistrations\Tables;

use App\Models\ExportLog;
use App\Models\ResultRegistration;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ResultRegistrationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('created_at')->dateTime('d M Y')->sortable()->label('Received'),
                TextColumn::make('name')->searchable(),
                TextColumn::make('mobile')->searchable()->toggleable(),
                TextColumn::make('email')->searchable()->toggleable(),
                TextColumn::make('exam.name')->label('Exam')->searchable(),
                TextColumn::make('roll_number')->label('Roll')->searchable(),
                TextColumn::make('legacy_zone')->label('Legacy zone')->toggleable()
                    ->tooltip('Only populated for pre-rebuild records imported from the legacy database.'),
            ])
            ->filters([
                SelectFilter::make('exam')->relationship('exam', 'name'),
            ])
            ->recordActions([EditAction::make()])
            ->headerActions([
                Action::make('export')
                    ->label('Export Excel (CSV)')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->visible(fn () => auth()->user()?->hasRole(User::ROLE_ADMIN, User::ROLE_OPS))
                    ->action(function (array $data, $livewire) {
                        // Respect currently active filters
                        $query = $livewire->getFilteredTableQuery();
                        $rows = $query->with('exam')->get();
                        $filename = 'result-registrations-'.now()->format('Ymd-His').'.csv';

                        ExportLog::create([
                            'actor_id' => auth()->id(),
                            'module' => 'result_registrations',
                            'filters_json' => $livewire->tableFilters ?? [],
                            'row_count' => $rows->count(),
                        ]);

                        return new StreamedResponse(function () use ($rows) {
                            $out = fopen('php://output', 'w');
                            fputcsv($out, ['Received', 'Name', 'Mobile', 'Email', 'Exam', 'Roll number', 'Legacy zone']);
                            foreach ($rows as $r) {
                                fputcsv($out, [
                                    $r->created_at->toDateTimeString(),
                                    self::safeCell($r->name),
                                    self::safeCell($r->mobile),
                                    self::safeCell($r->email),
                                    self::safeCell($r->exam?->name ?? ''),
                                    self::safeCell($r->roll_number),
                                    self::safeCell($r->legacy_zone ?? ''),
                                ]);
                            }
                            fclose($out);
                        }, 200, [
                            'Content-Type' => 'text/csv; charset=UTF-8',
                            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
                        ]);
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }

    /**
     * Neutralise spreadsheet-formula input (PRD: subscriber-facing fields).
     * Prepend a single quote where the first char is one of =, +, -, @.
     */
    private static function safeCell(?string $value): string
    {
        $v = (string) $value;
        if ($v !== '' && str_contains('=+-@', $v[0])) {
            return "'".$v;
        }
        return $v;
    }
}
