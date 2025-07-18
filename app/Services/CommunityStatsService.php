<?php

namespace App\Services;

use App\Models\Article;
use App\Models\News;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class CommunityStatsService
{
    /**
     * Cache duration for community stats (5 minutes).
     */
    const CACHE_DURATION = 5 * 60;

    /**
     * Get all community statistics with caching.
     */
    public static function getCommunityStats()
    {
        return Cache::remember('community.global.stats', self::CACHE_DURATION, function () {
            $totalMembers = User::count();
            $countriesCovered = User::whereNotNull('country_residence')
                ->distinct('country_residence')
                ->count();
            $totalContent = Article::count() + News::count();
            $thailandMembers = User::where('country_residence', 'Thaïlande')->count();

            \Log::info('Community stats calculated via service', [
                'totalMembers' => $totalMembers,
                'countriesCovered' => $countriesCovered,
                'totalContent' => $totalContent,
                'thailandMembers' => $thailandMembers,
            ]);

            return [
                'totalMembers' => $totalMembers,
                'countriesCovered' => $countriesCovered,
                'totalContent' => $totalContent,
                'thailandMembers' => $thailandMembers,
            ];
        });
    }

    /**
     * Get total members count.
     */
    public static function getTotalMembers()
    {
        $stats = self::getCommunityStats();

        return $stats['totalMembers'];
    }

    /**
     * Get countries covered count.
     */
    public static function getCountriesCovered()
    {
        $stats = self::getCommunityStats();

        return $stats['countriesCovered'];
    }

    /**
     * Get total content count.
     */
    public static function getTotalContent()
    {
        $stats = self::getCommunityStats();

        return $stats['totalContent'];
    }

    /**
     * Get Thailand members count.
     */
    public static function getThailandMembers()
    {
        $stats = self::getCommunityStats();

        return $stats['thailandMembers'];
    }

    /**
     * Clear community stats cache.
     */
    public static function clearCache()
    {
        Cache::forget('community.global.stats');
        Cache::forget('community.stats'); // Old cache key from HomeController
    }
}
