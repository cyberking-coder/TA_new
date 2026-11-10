<?php

namespace App\Providers;

use App\Services\Payments\CCAvenueAdapter;
use App\Services\Payments\LocalSimulatorProvider;
use App\Services\Payments\PaymentProvider;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(PaymentProvider::class, function ($app) {
            $driver = config('payments.provider');

            return match ($driver) {
                'ccavenue' => new CCAvenueAdapter(
                    merchantId: (string) config('payments.ccavenue.merchant_id'),
                    accessCode: (string) config('payments.ccavenue.access_code'),
                    workingKey: (string) config('payments.ccavenue.working_key'),
                    actionUrl:  (string) config('payments.ccavenue.action_url'),
                ),
                default => new LocalSimulatorProvider(
                    secret: (string) config('payments.simulator_secret'),
                ),
            };
        });
    }
}
