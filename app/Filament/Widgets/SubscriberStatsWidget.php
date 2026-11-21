<?php

namespace App\Filament\Widgets;

use App\Models\AppSetting;
use App\Models\Exam;
use App\Models\OfficerSubscriber;
use App\Models\ResultRegistration;
use App\Models\SoldierSubscriber;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SubscriberStatsWidget extends BaseWidget
{
    protected ?string $heading = 'SMS Alert & examination registrations';
    protected ?string $description = 'Live counts across GD / Officer / Result-notification audiences.';
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $soldierTotal       = SoldierSubscriber::count();
        $soldierSubscribed  = SoldierSubscriber::whereNull('unsubscribed_at')->count();
        $soldierUnsub       = SoldierSubscriber::whereNotNull('unsubscribed_at')->count();

        $officerTotal       = OfficerSubscriber::count();
        $officerSubscribed  = OfficerSubscriber::whereNull('unsubscribed_at')->count();
        $officerUnsub       = OfficerSubscriber::whereNotNull('unsubscribed_at')->count();

        $resultTotal        = ResultRegistration::count();
        $publishedExams     = Exam::published()->count();

        return [
            Stat::make('GD / Soldier subscribers', $soldierSubscribed)
                ->description($soldierUnsub.' unsubscribed · '.$soldierTotal.' records')
                ->descriptionIcon('heroicon-m-device-phone-mobile')
                ->color('success'),

            Stat::make('Officer subscribers', $officerSubscribed)
                ->description($officerUnsub.' unsubscribed · '.$officerTotal.' records')
                ->descriptionIcon('heroicon-m-device-phone-mobile')
                ->color('success'),

            Stat::make('TA Office exam registrations', $resultTotal)
                ->description($publishedExams.' published exam(s) accepting registrations')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('primary'),

            Stat::make('OTP verification',
                AppSetting::bool('otp_enabled', false) ? 'ON' : 'OFF')
                ->description(AppSetting::bool('otp_enabled', false)
                    ? 'Candidates must verify a 6-digit code'
                    : 'Candidates register without OTP')
                ->descriptionIcon(AppSetting::bool('otp_enabled', false)
                    ? 'heroicon-m-shield-check' : 'heroicon-m-shield-exclamation')
                ->color(AppSetting::bool('otp_enabled', false) ? 'success' : 'gray'),

            Stat::make('Result notification page',
                AppSetting::bool('result_notification_enabled', true) ? 'ON' : 'OFF')
                ->description(AppSetting::bool('result_notification_enabled', true)
                    ? '/result/register is live' : '/result/register returns 404')
                ->descriptionIcon(AppSetting::bool('result_notification_enabled', true)
                    ? 'heroicon-m-eye' : 'heroicon-m-eye-slash')
                ->color(AppSetting::bool('result_notification_enabled', true) ? 'success' : 'warning'),
        ];
    }
}
