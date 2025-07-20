<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class LocationController extends Controller
{
    /**
     * Get approximate location from IP address as fallback
     */
    public function getLocationFromIp(Request $request)
    {
        try {
            // Get client IP
            $ip = $request->ip();
            
            // For local development, use a test IP
            if (in_array($ip, ['127.0.0.1', '::1', 'localhost'])) {
                $ip = '8.8.8.8'; // Google DNS for testing
            }
            
            // Cache key for this IP
            $cacheKey = "ip_location_{$ip}";
            
            // Check cache first
            if (Cache::has($cacheKey)) {
                return response()->json(Cache::get($cacheKey));
            }
            
            // Try ip-api.com (free, no key required)
            $response = Http::timeout(5)->get("http://ip-api.com/json/{$ip}?fields=status,message,country,countryCode,city,lat,lon");
            
            if ($response->successful()) {
                $data = $response->json();
                
                if ($data['status'] === 'success') {
                    $result = [
                        'success' => true,
                        'method' => 'ip',
                        'country' => $data['country'],
                        'countryCode' => $data['countryCode'],
                        'city' => $data['city'],
                        'lat' => $data['lat'],
                        'lng' => $data['lon'],
                        'accuracy' => 'approximate'
                    ];
                    
                    // Cache for 1 day
                    Cache::put($cacheKey, $result, 86400);
                    
                    return response()->json($result);
                }
            }
            
            // Fallback response
            return response()->json([
                'success' => false,
                'message' => 'Unable to determine location from IP'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('IP geolocation error', [
                'error' => $e->getMessage(),
                'ip' => $request->ip()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Location service error'
            ]);
        }
    }
}