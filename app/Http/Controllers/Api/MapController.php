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
     * Geocoding and reverse geocoding API
     * Supports both text queries (?q=...) and coordinate-based reverse geocoding (?lat=...&lng=...)
     */
    public function geocode(Request $request): JsonResponse
    {
        $query = $request->input('q');
        $lat = $request->input('lat');
        $lng = $request->input('lng');
        
        // Check if this is a reverse geocoding request (coordinates to address)
        if ($lat && $lng) {
            return $this->reverseGeocode((float) $lat, (float) $lng);
        }
        
        // Forward geocoding (text query to coordinates)
        if (!$query) {
            return response()->json(['error' => 'Query parameter (q) or coordinates (lat, lng) required'], 400);
        }
        
        $accessToken = config('services.mapbox.access_token');
        
        if (!$accessToken) {
            return response()->json(['error' => 'Service not available'], 503);
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
     * Reverse geocoding using OpenStreetMap Nominatim API
     * Converts coordinates to address information
     */
    private function reverseGeocode(float $lat, float $lng): JsonResponse
    {
        // Validate coordinates
        if ($lat < -90 || $lat > 90 || $lng < -180 || $lng > 180) {
            return response()->json(['error' => 'Invalid coordinates'], 400);
        }
        
        // Cache reverse geocoding results for 1 hour
        $cacheKey = 'reverse_geocode.' . md5("{$lat},{$lng}");
        
        $result = Cache::remember($cacheKey, 3600, function () use ($lat, $lng) {
            try {
                // Use OpenStreetMap Nominatim for reverse geocoding (free and reliable)
                $response = Http::timeout(10)
                    ->withHeaders([
                        'User-Agent' => 'Sekaijin/1.0 (contact@sekaijin.com)'
                    ])
                    ->get('https://nominatim.openstreetmap.org/reverse', [
                        'format' => 'json',
                        'lat' => $lat,
                        'lon' => $lng,
                        'zoom' => 10,
                        'addressdetails' => 1,
                        'accept-language' => 'en,fr'
                    ]);
                
                if (!$response->successful()) {
                    return null;
                }
                
                $data = $response->json();
                
                if (!$data || !isset($data['address'])) {
                    return null;
                }
                
                $address = $data['address'];
                
                // Extract city name from various possible fields, preferring Latin characters
                $city = $address['city'] ?? 
                       $address['town'] ?? 
                       $address['village'] ?? 
                       $address['county'] ?? 
                       $address['state'] ?? 
                       'Ville inconnue';
                
                // Get country code and name
                $countryCode = $address['country_code'] ?? '';
                $countryName = $address['country'] ?? '';
                
                // Map some common country codes to French names
                $countryMappings = [
                    'fr' => 'France',
                    'th' => 'Thaïlande', 
                    'jp' => 'Japon',
                    'us' => 'États-Unis',
                    'ca' => 'Canada',
                    'de' => 'Allemagne',
                    'gb' => 'Royaume-Uni',
                    'es' => 'Espagne',
                    'it' => 'Italie',
                    'ch' => 'Suisse',
                    'be' => 'Belgique',
                    'au' => 'Australie',
                    'nl' => 'Pays-Bas',
                    'pt' => 'Portugal',
                    'at' => 'Autriche'
                ];
                
                $countryDisplayName = $countryMappings[strtolower($countryCode)] ?? $countryName;
                
                return [
                    'success' => true,
                    'city' => $city,
                    'country' => strtoupper($countryCode),
                    'countryName' => $countryDisplayName,
                    'full_address' => $data['display_name'] ?? '',
                    'coordinates' => [
                        'lat' => $lat,
                        'lng' => $lng
                    ]
                ];
                
            } catch (\Exception $e) {
                \Log::warning('Reverse geocoding failed', [
                    'lat' => $lat,
                    'lng' => $lng,
                    'error' => $e->getMessage()
                ]);
                return null;
            }
        });
        
        if ($result) {
            return response()->json($result);
        }
        
        return response()->json([
            'success' => false,
            'error' => 'Unable to determine location from coordinates'
        ], 500);
    }
}