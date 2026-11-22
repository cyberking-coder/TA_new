<?php

namespace App\Filament\Pages;

use App\Models\AppSetting;
use Filament\Actions\Action;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;

class Settings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $title = 'Site Settings';
    protected static ?string $navigationLabel = 'Site Settings';
    protected static string|\UnitEnum|null $navigationGroup = 'People & Exams';
    protected static ?int $navigationSort = 99;

    protected string $view = 'filament.pages.settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'otp_enabled'                 => AppSetting::bool('otp_enabled', false),
            'result_notification_enabled' => AppSetting::bool('result_notification_enabled', true),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Verification')
                    ->description('When OTP is ON, every new subscription and result registration must verify a 6-digit code sent to the candidate\'s mobile. Rate-limited; codes expire in 10 minutes.')
                    ->schema([
                        Toggle::make('otp_enabled')
                            ->label('Mobile OTP verification')
                            ->helperText('ON = candidates must verify their mobile with a one-time code. OFF = they can register directly.')
                            ->inline(false),
                    ]),

                Section::make('TA Office Written Examination')
                    ->description('Controls whether the public "Register for result notifications" page is reachable at all.')
                    ->schema([
                        Toggle::make('result_notification_enabled')
                            ->label('Result notification registration page')
                            ->helperText('ON = /result/register and its cross-link from SMS Alert pages are visible. OFF = returns 404.')
                            ->inline(false),
                    ]),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Save settings')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();
        AppSetting::set('otp_enabled', (bool) ($data['otp_enabled'] ?? false), auth()->id());
        AppSetting::set('result_notification_enabled', (bool) ($data['result_notification_enabled'] ?? true), auth()->id());

        Notification::make()->title('Settings saved')->success()->send();
    }
}
