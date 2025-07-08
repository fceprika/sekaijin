<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class MapController extends Controller
{
    /**
     * Proxy for Mapbox API to hide access token
     */
    public function getMapConfig(): JsonResponse
    {
        $accessToken = config('services.mapbox.access_token');
        
        if (!$accessToken) {
            return response()->json([
                'error' => 'Map service not configured'
            ], 503);
        }
        
        // Return limited public token or use restricted token
        return response()->json([
            'mapStyle' => 'mapbox://styles/mapbox/streets-v12',
            'accessToken' => $accessToken, // In production, use a restricted public token
            'center' => [100.5018, 13.7563], // Thailand center
            'zoom' => 3
        ]);
    }
    
    /**
     * Proxy for Mapbox geocoding API (forward and reverse)
     */
    public function geocode(Request $request): JsonResponse
    {
        $accessToken = config('services.mapbox.access_token');
        
        if (!$accessToken) {
            return response()->json(['error' => 'Service not available'], 503);
        }

        // Check if it's reverse geocoding (lat, lng provided)
        $lat = $request->input('lat');
        $lng = $request->input('lng');
        
        if ($lat && $lng) {
            return $this->reverseGeocode($lat, $lng, $accessToken);
        }

        // Forward geocoding
        $query = $request->input('q');
        
        if (!$query) {
            return response()->json(['error' => 'Query parameter or lat/lng required'], 400);
        }
        
        // Cache geocoding results for 1 hour
        $cacheKey = 'geocode.' . md5($query);
        
        $result = Cache::remember($cacheKey, 3600, function () use ($query, $accessToken) {
            $response = Http::get("https://api.mapbox.com/geocoding/v5/mapbox.places/{$query}.json", [
                'access_token' => $accessToken,
                'limit' => 5,
                'types' => 'place,locality,neighborhood'
            ]);
            
            if ($response->successful()) {
                return $response->json();
            }
            
            return null;
        });
        
        if ($result) {
            return response()->json($result);
        }
        
        return response()->json(['error' => 'Geocoding failed'], 500);
    }

    /**
     * Reverse geocoding - get place info from coordinates
     */
    private function reverseGeocode($lat, $lng, $accessToken): JsonResponse
    {
        // Cache reverse geocoding results for 24 hours (coordinates don't change often)
        $cacheKey = 'reverse_geocode.' . md5("{$lat},{$lng}");
        
        $result = Cache::remember($cacheKey, 86400, function () use ($lat, $lng, $accessToken) {
            $response = Http::get("https://api.mapbox.com/geocoding/v5/mapbox.places/{$lng},{$lat}.json", [
                'access_token' => $accessToken,
                'types' => 'place,locality,region,country'
            ]);
            
            if (!$response->successful()) {
                return null;
            }

            $data = $response->json();
            $features = $data['features'] ?? [];
            
            if (empty($features)) {
                return null;
            }

            // Extract country and city information
            $country = null;
            $countryCode = null;
            $city = null;
            $region = null;

            foreach ($features as $feature) {
                $placeType = $feature['place_type'][0] ?? null;
                $properties = $feature['properties'] ?? [];
                $text = $feature['text'] ?? '';

                switch ($placeType) {
                    case 'country':
                        $country = $text;
                        $countryCode = $properties['short_code'] ?? null;
                        break;
                    case 'region':
                        $region = $text;
                        break;
                    case 'place':
                    case 'locality':
                        if (!$city) { // Take the first city found
                            $city = $text;
                        }
                        break;
                }
            }

            // If no city found, try to use region
            if (!$city && $region) {
                $city = $region;
            }

            return [
                'country' => $countryCode ? strtoupper($countryCode) : null,
                'countryName' => $country,
                'city' => $city,
                'region' => $region,
                'coordinates' => ['lat' => $lat, 'lng' => $lng]
            ];
        });
        
        if ($result && $result['country'] && $result['city']) {
            return response()->json([
                'success' => true,
                'country' => $result['country'],
                'countryName' => $result['countryName'],
                'city' => $result['city'],
                'region' => $result['region'],
                'coordinates' => $result['coordinates']
            ]);
        }
        
        return response()->json([
            'success' => false,
            'error' => 'Could not determine location from coordinates'
        ], 404);
    }
}