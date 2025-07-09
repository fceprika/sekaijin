<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\SeoService;

class SeoServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(SeoService::class, function ($app) {
            return new SeoService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}