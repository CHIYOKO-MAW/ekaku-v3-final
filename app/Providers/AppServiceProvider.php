<?php

namespace App\Providers;

use App\Events\BiodataStatusChanged;
use App\Listeners\SendBiodataStatusNotification;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (class_exists(\Laravel\Telescope\TelescopeApplicationServiceProvider::class)) {
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        // Prefetch Vite assets on idle (Laravel built-in)
        if (config('app.env') === 'production') {
            Vite::prefetch(concurrency: 3);
        }

        // Listen for biodata status changes and send notification
        Event::listen(
            BiodataStatusChanged::class,
            SendBiodataStatusNotification::class
        );
    }
}
