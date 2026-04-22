<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiting\Limit;
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

        // Rate limiters
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinutes(5, 10)->by($request->input('email') . '|' . $request->ip());
        });

        RateLimiter::for('repair-request', function (Request $request) {
            return Limit::perMinutes(15, 3)->by($request->ip());
        });

        RateLimiter::for('ticket-rating', function (Request $request) {
            $userId = $request->user()?->id ?? 'guest';
            $ticketId = $request->route('ticket') ?? 'unknown';
            return Limit::perMinutes(5, 1)->by($userId . '|' . $ticketId);
        });
    }
}
