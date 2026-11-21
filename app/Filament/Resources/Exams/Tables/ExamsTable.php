<?php

namespace App\Filament\Resources\Exams\Tables;

use App\Models\Exam;
use App\Models\ResultRegistration;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ExamsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('short_code')->searchable()->toggleable(),
                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'gray'    => Exam::STATUS_DRAFT,
                        'success' => Exam::STATUS_PUBLISHED,
                        'warning' => Exam::STATUS_UNPUBLISHED,
                    ])
                    ->sortable(),
                TextColumn::make('result_registrations_count')
                    ->counts('resultRegistrations')
                    ->label('Registrations')
                    ->sortable(),
                TextColumn::make('published_at')->dateTime()->sortable()->toggleable(),
                TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    Exam::STATUS_DRAFT       => 'Draft',
                    Exam::STATUS_PUBLISHED   => 'Published',
                    Exam::STATUS_UNPUBLISHED => 'Unpublished',
                ]),
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make(),

                    Action::make('publish')
                        ->label('Publish')
                        ->icon('heroicon-o-eye')
                        ->color('success')
                        ->visible(fn (Exam $r) => $r->status !== Exam::STATUS_PUBLISHED)
                        ->requiresConfirmation()
                        ->action(function (Exam $r) {
                            $r->update(['status' => Exam::STATUS_PUBLISHED]);
                            Notification::make()->title('Exam published')->success()->send();
                        }),

                    Action::make('unpublish')
                        ->label('Unpublish')
                        ->icon('heroicon-o-eye-slash')
                        ->color('warning')
                        ->visible(fn (Exam $r) => $r->status === Exam::STATUS_PUBLISHED)
                        ->requiresConfirmation()
                        ->action(function (Exam $r) {
                            $r->update(['status' => Exam::STATUS_UNPUBLISHED]);
                            Notification::make()->title('Exam unpublished')->warning()->send();
                        }),

                    Action::make('mark_draft')
                        ->label('Mark as draft')
                        ->icon('heroicon-o-document')
                        ->color('gray')
                        ->visible(fn (Exam $r) => $r->status !== Exam::STATUS_DRAFT)
                        ->requiresConfirmation()
                        ->action(function (Exam $r) {
                            $r->update(['status' => Exam::STATUS_DRAFT]);
                            Notification::make()->title('Exam moved to draft')->send();
                        }),

                    Action::make('delete_all_registrations')
                        ->label('Delete ALL registrations')
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Delete every registration for this exam?')
                        ->modalDescription(fn (Exam $r) => 'This removes all '.$r->resultRegistrations()->count().' registration(s) for "'.$r->name.'". The exam itself is kept. This cannot be undone.')
                        ->modalSubmitActionLabel('Delete registrations')
                        ->action(function (Exam $r) {
                            $n = $r->resultRegistrations()->delete();
                            // Audit trail: write to the existing export_logs table.
                            \App\Models\ExportLog::create([
                                'actor_id'    => auth()->id(),
                                'module'      => 'result_registrations',
                                'filters_json'=> ['action' => 'bulk_delete_by_exam', 'exam_id' => $r->id],
                                'row_count'   => $n,
                            ]);
                            Notification::make()
                                ->title($n.' registration(s) deleted for "'.$r->name.'"')
                                ->success()
                                ->send();
                        }),
                ])->label('Actions'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
