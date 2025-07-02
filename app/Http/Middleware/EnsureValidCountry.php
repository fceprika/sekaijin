<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Country;

class EnsureValidCountry
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $countrySlug = $request->route('country');
        
        if (!$countrySlug) {
            abort(404, 'Pays non spécifié');
        }

        $country = Country::where('slug', $countrySlug)->first();
        
        if (!$country) {
            abort(404, 'Pays non trouvé');
        }

        // Make country available in all views
        view()->share('currentCountry', $country);
        
        // Make country available in request
        $request->merge(['country_model' => $country]);

        return $next($request);
    }
}
