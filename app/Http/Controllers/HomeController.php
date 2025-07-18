<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\CommunityStatsService;
use App\Services\ContentCacheService;
use App\Services\SeoService;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        try {
            // Cache configuration - 15 minutes for content, 5 minutes for stats
            $contentCacheTime = 15 * 60; // 15 minutes
            $statsCacheTime = 5 * 60; // 5 minutes

            // Get Thailand content using optimized cache service
            $thailandContent = ContentCacheService::getThailandContent();

            if (! $thailandContent['country']) {
                throw new \Exception('Thailand country not found');
            }

            // Get cached community stats
            $communityStats = CommunityStatsService::getCommunityStats();

            // Cache recent members (short cache for freshness)
            $recentMembers = Cache::remember('members.recent', $statsCacheTime, function () {
                return User::select('id', 'name', 'avatar', 'country_residence', 'city_residence', 'created_at')
                    ->orderBy('created_at', 'desc')
                    ->take(3)
                    ->get();
            });

            // Generate SEO data for homepage
            $seoService = new SeoService;
            $seoData = $seoService->generateSeoData('home');
            $structuredData = $seoService->generateStructuredData('home');

            return view('home', [
                'thailand' => $thailandContent['country'],
                'thailandNews' => $thailandContent['news'],
                'thailandArticles' => $thailandContent['articles'],
                'thailandEvents' => $thailandContent['events'],
                'totalMembers' => $communityStats['totalMembers'],
                'thailandMembers' => $communityStats['thailandMembers'],
                'recentMembers' => $recentMembers,
                'seoData' => $seoData,
                'structuredData' => $structuredData,
            ]);

        } catch (\Exception $e) {
            \Log::error('HomeController error: ' . $e->getMessage());

            // Return fallback data
            $fallbackData = [
                'thailand' => null,
                'thailandNews' => collect(),
                'thailandArticles' => collect(),
                'thailandEvents' => collect(),
                'totalMembers' => 0,
                'thailandMembers' => 0,
                'recentMembers' => collect(),
            ];

            // Add SEO data even for fallback
            $seoService = new SeoService;
            $fallbackData['seoData'] = $seoService->generateSeoData('home');
            $fallbackData['structuredData'] = $seoService->generateStructuredData('home');

            return view('home', $fallbackData);
        }
    }

    /**
     * Clear homepage cache (useful for admin actions).
     */
    public function clearCache()
    {
        // Clear all cache services
        ContentCacheService::clearCache();
        CommunityStatsService::clearCache();

        // Clear remaining specific caches
        Cache::forget('members.recent');

        return response()->json(['message' => 'All homepage caches cleared successfully']);
    }
}
