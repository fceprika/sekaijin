<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class TurnstileServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register a custom Blade directive to check if Turnstile should be enabled
        Blade::if('turnstileEnabled', function () {
            // Only enable Turnstile if:
            // 1. Turnstile is properly configured with both key and secret
            // 2. AND either:
            //    - Not in local/testing environment, OR
            //    - In local/testing but bypass_local is disabled (false)
            $isConfigured = config('services.turnstile.key') && config('services.turnstile.secret');
            $isLocalEnvironment = app()->environment(['local', 'testing']);
            $bypassLocal = config('services.turnstile.bypass_local', false);
            
            // Show Turnstile if configured AND (not local OR local but bypass disabled)
            return $isConfigured && (!$isLocalEnvironment || !$bypassLocal);
        });
    }
}