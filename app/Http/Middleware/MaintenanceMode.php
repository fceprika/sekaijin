<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class MaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if maintenance mode is enabled
        if (config('app.maintenance_mode', false)) {
            // Allow access for admins (optional)
            if (auth()->check() && auth()->user()->isAdmin()) {
                return $next($request);
            }

            // Allow access to certain routes (login, logout, etc.)
            $allowedRoutes = [
                'login',
                'logout',
                'maintenance.status',
            ];

            if (in_array($request->route()->getName(), $allowedRoutes)) {
                return $next($request);
            }

            // Show maintenance page
            return response()->view('maintenance', [], 503);
        }

        return $next($request);
    }
}
