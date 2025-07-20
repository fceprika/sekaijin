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
            // Try to get real IP (considering proxies, load balancers, etc.)
            $ip = $this->getRealIpAddress($request);
            
            // Log for debugging without sensitive data (GDPR compliance)
            \Log::info('IP geolocation request', [
                'ip_type' => filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) ? 'IPv4' : 'IPv6',
                'is_local' => in_array($ip, ['127.0.0.1', '::1', 'localhost']),
                'has_proxy_headers' => !empty($request->header('X-Forwarded-For')),
            ]);
            
            // For local development, don't use test IP - return local info
            if (in_array($ip, ['127.0.0.1', '::1', 'localhost'])) {
                // Return a default location for local development
                return response()->json([
                    'success' => true,
                    'method' => 'local_default',
                    'country' => 'France',
                    'countryCode' => 'FR',
                    'city' => 'Paris',
                    'lat' => 48.8566,
                    'lng' => 2.3522,
                    'accuracy' => 'local_development',
                    'message' => 'Position par défaut pour développement local'
                ]);
            }
            
            // Cache key for this IP
            $cacheKey = "ip_location_{$ip}";
            
            // Check cache first
            if (Cache::has($cacheKey)) {
                return response()->json(Cache::get($cacheKey));
            }
            
            // Try ip-api.com (free, no key required) - HTTPS for security
            $response = Http::timeout(5)->get("https://ip-api.com/json/{$ip}?fields=status,message,country,countryCode,city,lat,lon");
            
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
    
    /**
     * Get real IP address considering proxies and load balancers
     */
    private function getRealIpAddress(Request $request): string
    {
        // Check for Cloudflare
        if ($cfIp = $request->header('CF-Connecting-IP')) {
            return $cfIp;
        }
        
        // Check for standard proxy headers
        if ($xForwardedFor = $request->header('X-Forwarded-For')) {
            // X-Forwarded-For can contain multiple IPs, get the first one
            $ips = explode(',', $xForwardedFor);
            return trim($ips[0]);
        }
        
        if ($xRealIp = $request->header('X-Real-IP')) {
            return $xRealIp;
        }
        
        // Fallback to Laravel's IP detection
        return $request->ip();
    }
}