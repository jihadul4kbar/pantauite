<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;

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
        // Force set locale to Indonesian
        config(['app.locale' => 'id']);
        config(['app.fallback_locale' => 'id']);
        app()->setLocale('id');
        
        // Set Carbon locale to Indonesian for diffForHumans
        Carbon::setLocale('id');
    }
}
