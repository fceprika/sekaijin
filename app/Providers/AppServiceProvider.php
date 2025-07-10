<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;

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
        // Register Blade directives for role checking
        Blade::directive('role', function ($role) {
            return "<?php if(auth()->check() && auth()->user()->isRole($role)): ?>";
        });

        Blade::directive('endrole', function () {
            return "<?php endif; ?>";
        });

        Blade::directive('admin', function () {
            return "<?php if(auth()->check() && auth()->user()->isAdmin()): ?>";
        });

        Blade::directive('endadmin', function () {
            return "<?php endif; ?>";
        });

        Blade::directive('premium', function () {
            return "<?php if(auth()->check() && auth()->user()->isPremium()): ?>";
        });

        Blade::directive('endpremium', function () {
            return "<?php endif; ?>";
        });

        Blade::directive('ambassador', function () {
            return "<?php if(auth()->check() && auth()->user()->isAmbassador()): ?>";
        });

        Blade::directive('endambassador', function () {
            return "<?php endif; ?>";
        });

        // Register view composers for performance optimization
        View::composer(['layout', 'country.*'], \App\Http\View\Composers\CountryComposer::class);
        View::composer(['layout', 'home', 'auth.*', 'emails.*'], \App\Http\View\Composers\StatsComposer::class);
    }
}
