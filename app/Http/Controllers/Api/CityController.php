<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CityController extends Controller
{
    /**
     * Get cities for a given country using local data
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
        
        // Cache the cities for 24 hours 
        $cacheKey = 'cities_' . str_replace(' ', '_', strtolower($country));
        
        $cities = Cache::remember($cacheKey, 24 * 60 * 60, function () use ($country) {
            try {
                // Load local cities data
                $citiesData = require database_path('data/cities_data.php');
                
                // Check if country exists in our data
                if (isset($citiesData[$country])) {
                    $cities = $citiesData[$country];
                    // Cities are already sorted in the data file
                    return $cities;
                }
                
                return [];
            } catch (\Exception $e) {
                \Log::warning('Failed to load cities for country: ' . $country, [
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
    
    /**
     * Get list of supported countries (Europe and Asia only)
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSupportedCountries()
    {
        $cacheKey = 'supported_countries';
        
        $countries = Cache::remember($cacheKey, 24 * 60 * 60, function () {
            try {
                // Load local cities data
                $citiesData = require database_path('data/cities_data.php');
                
                // Return only the country names (keys of the array)
                return array_keys($citiesData);
            } catch (\Exception $e) {
                \Log::warning('Failed to load supported countries', [
                    'error' => $e->getMessage()
                ]);
                return [];
            }
        });
        
        return response()->json([
            'success' => true,
            'countries' => $countries
        ]);
    }
}