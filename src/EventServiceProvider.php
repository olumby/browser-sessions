<?php

namespace Lumby\BetterBrowserSessions;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Lumby\BetterBrowserSessions\Listeners\StartTrackingBrowserSession;
use Lumby\BetterBrowserSessions\Listeners\StopTrackingBrowserSession;

class EventServiceProvider extends ServiceProvider
{

    protected $listen = [
        Login::class => [
            StartTrackingBrowserSession::class,
        ],
        Logout::class => [
            StopTrackingBrowserSession::class
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
