<?php

namespace App\Providers;

use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Vite;
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
        // Only force HTTPS in production when not running locally
        if (config('app.env') === 'production' && !app()->environment('local')) {
            \URL::forceScheme('https');
        }
        /*Vite::macro('image', fn ($asset) => $this->asset("storage/app/public/img/{$asset}"));*/
    }
}
