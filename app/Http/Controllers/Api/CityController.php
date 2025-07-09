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
                // Load local cities data from JSON file
                $jsonPath = database_path('data/cities_data.json');
                
                if (!file_exists($jsonPath)) {
                    \Log::warning('Cities data file not found: ' . $jsonPath);
                    return [];
                }
                
                $jsonContent = file_get_contents($jsonPath);
                $citiesData = json_decode($jsonContent, true);
                
                if (json_last_error() !== JSON_ERROR_NONE) {
                    \Log::warning('Failed to decode cities JSON: ' . json_last_error_msg());
                    return [];
                }
                
                // Check if country exists in our data
                if (isset($citiesData[$country])) {
                    $cities = $citiesData[$country];
                    // Extract just the city names from the objects
                    return array_map(function($city) {
                        return $city['name'];
                    }, $cities);
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
                // Load local cities data from JSON file
                $jsonPath = database_path('data/cities_data.json');
                
                if (!file_exists($jsonPath)) {
                    \Log::warning('Cities data file not found: ' . $jsonPath);
                    return [];
                }
                
                $jsonContent = file_get_contents($jsonPath);
                $citiesData = json_decode($jsonContent, true);
                
                if (json_last_error() !== JSON_ERROR_NONE) {
                    \Log::warning('Failed to decode cities JSON: ' . json_last_error_msg());
                    return [];
                }
                
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
    
    /**
     * Get coordinates for a specific city in a country
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCityCoordinates(Request $request)
    {
        $request->validate([
            'country' => 'required|string|max:100',
            'city' => 'required|string|max:100'
        ]);
        
        $country = $request->input('country');
        $city = $request->input('city');
        
        // Cache the coordinates for 24 hours
        $cacheKey = 'city_coords_' . str_replace(' ', '_', strtolower($country)) . '_' . str_replace(' ', '_', strtolower($city));
        
        $coordinates = Cache::remember($cacheKey, 24 * 60 * 60, function () use ($country, $city) {
            try {
                // Load local cities data from JSON file
                $jsonPath = database_path('data/cities_data.json');
                
                if (!file_exists($jsonPath)) {
                    \Log::warning('Cities data file not found: ' . $jsonPath);
                    return null;
                }
                
                $jsonContent = file_get_contents($jsonPath);
                $citiesData = json_decode($jsonContent, true);
                
                if (json_last_error() !== JSON_ERROR_NONE) {
                    \Log::warning('Failed to decode cities JSON: ' . json_last_error_msg());
                    return null;
                }
                
                // Check if country exists in our data
                if (isset($citiesData[$country])) {
                    $cities = $citiesData[$country];
                    
                    // Find the city
                    foreach ($cities as $cityData) {
                        if ($cityData['name'] === $city) {
                            return [
                                'lat' => $cityData['lat'],
                                'lng' => $cityData['lng']
                            ];
                        }
                    }
                }
                
                return null;
            } catch (\Exception $e) {
                \Log::warning('Failed to load city coordinates', [
                    'country' => $country,
                    'city' => $city,
                    'error' => $e->getMessage()
                ]);
                return null;
            }
        });
        
        if ($coordinates) {
            return response()->json([
                'success' => true,
                'country' => $country,
                'city' => $city,
                'coordinates' => $coordinates
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'City not found'
        ], 404);
    }
}