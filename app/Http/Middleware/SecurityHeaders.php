<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Content Security Policy - Different for dev and production
        $nonce = base64_encode(random_bytes(16));

        // Store nonce for use in views BEFORE processing the request
        app()->instance('csp_nonce', $nonce);
        view()->share('csp_nonce', $nonce);

        $response = $next($request);

        // Security headers to prevent various attacks
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(self)');

        if (app()->environment('local', 'development')) {
            // Development CSP - More permissive for Vite
            $csp = "default-src 'self'; " .
                   "script-src 'self' 'unsafe-inline' http://localhost:* https://cdn.tiny.cloud https://api.mapbox.com https://cdnjs.cloudflare.com https://challenges.cloudflare.com; " .
                   "style-src 'self' 'unsafe-inline' http://localhost:* https://api.mapbox.com https://cdnjs.cloudflare.com https://cdn.tiny.cloud; " .
                   "img-src 'self' data: https: http://localhost:*; " .
                   "font-src 'self' https://cdnjs.cloudflare.com http://localhost:*; " .
                   "connect-src 'self' ws://localhost:* http://localhost:* https://api.mapbox.com https://events.mapbox.com https://api.bigdatacloud.net https://cdn.tiny.cloud https://challenges.cloudflare.com; " .
                   "worker-src 'self' blob:; " .
                   "frame-ancestors 'none'; " .
                   "base-uri 'self'; " .
                   "form-action 'self'";
        } else {
            // Production CSP - Strict security (adapted for local testing)
            $localSources = app()->environment('local') ? ' http://localhost:* http://127.0.0.1:*' : '';
            $csp = "default-src 'self'{$localSources}; " .
                   "script-src 'self' 'nonce-{$nonce}' https://cdn.tiny.cloud https://api.mapbox.com https://cdnjs.cloudflare.com https://challenges.cloudflare.com https://www.googletagmanager.com{$localSources}; " .
                   "style-src 'self' 'unsafe-inline' https://api.mapbox.com https://cdnjs.cloudflare.com https://cdn.tiny.cloud{$localSources}; " .
                   "img-src 'self' data: https:{$localSources}; " .
                   "font-src 'self' https://cdnjs.cloudflare.com{$localSources}; " .
                   "connect-src 'self' https://api.mapbox.com https://events.mapbox.com https://api.bigdatacloud.net https://www.google-analytics.com https://analytics.google.com https://region1.google-analytics.com https://cdn.tiny.cloud https://challenges.cloudflare.com{$localSources}; " .
                   "worker-src 'self' blob:; " .
                   "frame-ancestors 'none'; " .
                   "base-uri 'self'; " .
                   "form-action 'self'";
        }

        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}
