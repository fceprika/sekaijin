<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ConditionalEmailVerification
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip email verification if configured to do so
        if (config('services.email_verification.skip', false)) {
            return $next($request);
        }

        // Otherwise use Laravel's default email verification middleware
        $emailVerificationMiddleware = new EnsureEmailIsVerified;

        return $emailVerificationMiddleware->handle($request, $next);
    }
}
