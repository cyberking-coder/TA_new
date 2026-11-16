<?php

namespace App\Providers;

use App\Services\Sms\Fast2SmsProvider;
use App\Services\Sms\LogSmsProvider;
use App\Services\Sms\SmsProvider;
use Illuminate\Support\ServiceProvider;

class SmsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(SmsProvider::class, function ($app) {
            $driver = config('services.sms.provider', 'log');

            return match ($driver) {
                'fast2sms' => new Fast2SmsProvider(
                    apiKey:    (string) config('services.fast2sms.api_key', ''),
                    route:     (string) config('services.fast2sms.route', 'q'),
                    senderId:  config('services.fast2sms.sender_id'),
                    messageId: config('services.fast2sms.message_id'),
                ),
                default => new LogSmsProvider(),
            };
        });
    }
}
