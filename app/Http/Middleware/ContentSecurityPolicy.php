<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ContentSecurityPolicy
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        // Only add CSP headers in production when Google Analytics is enabled
        if (app()->environment('production') && config('services.google_analytics.id')) {
            $csp = [
                "default-src 'self'",
                "script-src 'self' 'unsafe-inline' https://www.googletagmanager.com https://api.mapbox.com https://cdnjs.cloudflare.com",
                "style-src 'self' 'unsafe-inline' https://api.mapbox.com https://cdnjs.cloudflare.com",
                "img-src 'self' data: https://www.google-analytics.com https://api.mapbox.com",
                "connect-src 'self' https://www.google-analytics.com https://api.mapbox.com",
                "font-src 'self' https://cdnjs.cloudflare.com",
                "frame-src 'none'",
                "object-src 'none'",
                "base-uri 'self'"
            ];
            
            $response->headers->set('Content-Security-Policy', implode('; ', $csp));
        }
        
        return $response;
    }
}
