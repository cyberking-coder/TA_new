<?php

namespace App\Providers;

use App\Models\Newsletter;
use App\Models\OfficerSubscriber;
use App\Models\ResultRegistration;
use App\Models\SoldierSubscriber;
use App\Policies\SubscriberPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        OfficerSubscriber::class => SubscriberPolicy::class,
        SoldierSubscriber::class => SubscriberPolicy::class,
        ResultRegistration::class => SubscriberPolicy::class,
        Newsletter::class => SubscriberPolicy::class,
    ];
}
