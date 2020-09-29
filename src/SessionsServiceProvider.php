<?php

namespace Lumby\BetterBrowserSessions;

use Illuminate\Support\ServiceProvider;

class SessionsServiceProvider extends ServiceProvider
{
    protected $defer = false;

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->registerPublishables();
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->register(EventServiceProvider::class);
    }

    protected function registerPublishables(): void
    {
        if (! class_exists('CreateBrowserSessionsTable')) {
            $this->publishes([
                __DIR__.'/../database/migrations/create_browser_sessions_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_browser_sessions_table.php'),
            ], 'migrations');
        }
    }
}
