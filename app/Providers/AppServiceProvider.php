<?php

namespace App\Providers;

use App\Events\BiodataStatusChanged;
use App\Listeners\SendBiodataStatusNotification;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        // Listen for biodata status changes and send notification
        Event::listen(
            BiodataStatusChanged::class,
            SendBiodataStatusNotification::class
        );
    }
}
