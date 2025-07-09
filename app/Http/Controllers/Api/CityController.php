<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class CityController extends Controller
{
    /**
     * Get cities for a given country using CountriesNow API
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCities(Request $request)
    {
        $request->validate([
            'country' => 'required|string|max:100'
        ]);
        
        $country = $request->input('country');
        
        // Cache the cities for 24 hours to avoid too many API calls
        $cacheKey = 'cities_' . str_replace(' ', '_', strtolower($country));
        
        $cities = Cache::remember($cacheKey, 24 * 60 * 60, function () use ($country) {
            try {
                // Call CountriesNow API
                $response = Http::timeout(10)->post('https://countriesnow.space/api/v0.1/countries/cities', [
                    'country' => $country
                ]);
                
                if ($response->successful()) {
                    $data = $response->json();
                    
                    if (isset($data['data']) && is_array($data['data'])) {
                        // Sort cities alphabetically
                        $cities = $data['data'];
                        sort($cities, SORT_STRING | SORT_FLAG_CASE);
                        return $cities;
                    }
                }
                
                return [];
            } catch (\Exception $e) {
                \Log::warning('Failed to fetch cities for country: ' . $country, [
                    'error' => $e->getMessage()
                ]);
                return [];
            }
        });
        
        return response()->json([
            'success' => true,
            'country' => $country,
            'cities' => $cities
        ]);
    }
}