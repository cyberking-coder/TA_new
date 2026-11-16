<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
            if ($host = parse_url((string) config('app.url'), PHP_URL_HOST)) {
                URL::forceRootUrl('https://'.$host);
            }
        }

        RateLimiter::for('public-form', function (Request $request) {
            return [
                Limit::perMinute(10)->by($request->ip()),
                Limit::perHour(60)->by($request->ip()),
            ];
        });

        RateLimiter::for('otp-send', function (Request $request) {
            $mobile = (string) $request->input('mobile', $request->ip());
            return [
                Limit::perMinute(1)->by($mobile),
                Limit::perHour(3)->by($mobile),
            ];
        });

        RateLimiter::for('review-submit', function (Request $request) {
            return Limit::perHour(3)->by($request->ip().'|'.$request->route('slug'));
        });

        RateLimiter::for('login', function (Request $request) {
            return [
                Limit::perMinute(5)->by($request->ip()),
                Limit::perMinute(5)->by((string) $request->input('email')),
            ];
        });
    }
}
