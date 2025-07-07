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
     * Proxy for Mapbox geocoding API (if needed)
     */
    public function geocode(Request $request): JsonResponse
    {
        $query = $request->input('q');
        
        if (!$query) {
            return response()->json(['error' => 'Query parameter required'], 400);
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
}