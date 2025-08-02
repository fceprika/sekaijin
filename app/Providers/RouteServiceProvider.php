<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        // Custom route model binding for articles
        Route::bind('article', function ($value, $route) {
            // Check if this is an API route (starts with api/)
            $uri = $route->uri();
            $isApiRoute = str_starts_with($uri, 'api/');

            if ($isApiRoute) {
                // For API routes: always use ID
                return \App\Models\Article::findOrFail($value);
            }

            // For web routes: check if we're in a country context
            $country = $route->parameter('country');
            if ($country && is_string($country)) {
                $countryModel = \App\Models\Country::where('slug', $country)->first();
                if ($countryModel) {
                    return \App\Models\Article::where('slug', $value)
                        ->where('country_id', $countryModel->id)
                        ->firstOrFail();
                }
            }

            // Fallback: find by slug globally or by ID if numeric
            if (is_numeric($value)) {
                return \App\Models\Article::findOrFail($value);
            }

            return \App\Models\Article::where('slug', $value)->firstOrFail();
        });

        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // Custom rate limiting for News API (more restrictive due to image downloads)
        RateLimiter::for('api-news', function (Request $request) {
            return [
                // 30 requests per minute per user/IP
                Limit::perMinute(30)->by($request->user()?->id ?: $request->ip()),
                // 100 requests per hour per user/IP
                Limit::perHour(100)->by($request->user()?->id ?: $request->ip()),
            ];
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
