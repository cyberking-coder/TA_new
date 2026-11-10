<?php

namespace App\Filament\Resources\Newsletters\Tables;

use App\Models\Newsletter;
use App\Models\User;
use App\Services\Newsletters\NewsletterSender;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class NewslettersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('created_at')->dateTime('d M Y H:i')->sortable()->label('Created'),
                TextColumn::make('audience')->badge(),
                TextColumn::make('subject')->searchable()->limit(60),
                TextColumn::make('status')->badge()->color(fn ($s) => match ($s) {
                    'sent' => 'success',
                    'sending' => 'warning',
                    'cancelled' => 'danger',
                    default => 'gray',
                }),
                TextColumn::make('sent_count')->label('Sent'),
                TextColumn::make('skipped_count')->label('Skipped at send-time'),
                TextColumn::make('sent_at')->dateTime('d M Y H:i')->toggleable(),
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('test')
                    ->label('Send test')
                    ->color('gray')
                    ->icon('heroicon-o-paper-airplane')
                    ->visible(fn (Newsletter $n) => $n->status === Newsletter::STATUS_DRAFT)
                    ->schema([TextInput::make('recipient')->email()->required()])
                    ->action(function (Newsletter $n, array $data) {
                        app(NewsletterSender::class)->sendTest($n, $data['recipient']);
                        Notification::make()->success()->title('Test queued to '.$data['recipient'])->send();
                    }),
                Action::make('send')
                    ->label('Send to list')
                    ->color('success')
                    ->icon('heroicon-o-megaphone')
                    ->visible(fn (Newsletter $n) => $n->status === Newsletter::STATUS_DRAFT
                        && auth()->user()?->hasRole(User::ROLE_ADMIN, User::ROLE_OPS))
                    ->requiresConfirmation()
                    ->modalDescription(fn (Newsletter $n) => 'Suppressions are re-checked for every recipient at send time; this cannot be undone.')
                    ->action(function (Newsletter $n) {
                        $r = app(NewsletterSender::class)->send($n);
                        Notification::make()->success()
                            ->title('Sent to '.$r['sent'].' recipients')
                            ->body($r['skipped'].' addresses were skipped at send time (unsubscribed / suppressed).')
                            ->send();
                    }),
            ]);
    }
}
