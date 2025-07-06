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
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Security headers to prevent various attacks
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(self)');
        
        // Content Security Policy - Different for dev and production
        $nonce = base64_encode(random_bytes(16));
        
        if (app()->environment('local', 'development')) {
            // Development CSP - More permissive for Vite
            $csp = "default-src 'self'; " .
                   "script-src 'self' 'unsafe-inline' 'unsafe-eval' http://localhost:* https://cdn.tiny.cloud https://api.mapbox.com https://cdnjs.cloudflare.com; " .
                   "style-src 'self' 'unsafe-inline' http://localhost:* https://api.mapbox.com https://cdnjs.cloudflare.com; " .
                   "img-src 'self' data: https: http://localhost:*; " .
                   "font-src 'self' https://cdnjs.cloudflare.com http://localhost:*; " .
                   "connect-src 'self' ws://localhost:* http://localhost:* https://api.mapbox.com https://events.mapbox.com https://api.bigdatacloud.net; " .
                   "worker-src 'self' blob:; " .
                   "frame-ancestors 'none'; " .
                   "base-uri 'self'; " .
                   "form-action 'self'";
        } else {
            // Production CSP - Strict security
            $csp = "default-src 'self'; " .
                   "script-src 'self' 'nonce-{$nonce}' https://cdn.tiny.cloud https://api.mapbox.com https://cdnjs.cloudflare.com; " .
                   "style-src 'self' 'unsafe-inline' https://api.mapbox.com https://cdnjs.cloudflare.com; " .
                   "img-src 'self' data: https:; " .
                   "font-src 'self' https://cdnjs.cloudflare.com; " .
                   "connect-src 'self' https://api.mapbox.com https://events.mapbox.com https://api.bigdatacloud.net; " .
                   "worker-src 'self' blob:; " .
                   "frame-ancestors 'none'; " .
                   "base-uri 'self'; " .
                   "form-action 'self'";
        }
        
        $response->headers->set('Content-Security-Policy', $csp);
        
        // Store nonce for use in views
        view()->share('csp_nonce', $nonce);

        return $response;
    }
}
