<?php

namespace App\Services;

use App\Models\Country;
use App\Models\News;
use App\Models\Article; 
use App\Models\Event;
use Illuminate\Support\Facades\Cache;

class ContentCacheService
{
    /**
     * Cache duration for content (15 minutes)
     */
    const CONTENT_CACHE_DURATION = 15 * 60;
    
    /**
     * Get cached content for all countries to avoid cache fragmentation
     */
    public static function getAllCountriesContent()
    {
        return Cache::remember('content.all_countries', self::CONTENT_CACHE_DURATION, function () {
            $countries = Country::all();
            $content = [];
            
            foreach ($countries as $country) {
                $content[$country->slug] = [
                    'country' => $country,
                    'news' => News::where('country_id', $country->id)
                        ->where('is_published', true)
                        ->with(['author' => function($query) {
                            $query->select('id', 'name', 'avatar', 'is_verified');
                        }])
                        ->select('id', 'title', 'slug', 'excerpt', 'content', 'category', 'author_id', 'created_at')
                        ->orderBy('created_at', 'desc')
                        ->take(3)
                        ->get(),
                    'articles' => Article::where('country_id', $country->id)
                        ->where('is_published', true)
                        ->with(['author' => function($query) {
                            $query->select('id', 'name', 'avatar', 'is_verified');
                        }])
                        ->select('id', 'title', 'slug', 'excerpt', 'content', 'category', 'author_id', 'created_at')
                        ->orderBy('created_at', 'desc')
                        ->take(3)
                        ->get(),
                    'events' => Event::where('country_id', $country->id)
                        ->where('is_published', true)
                        ->with(['organizer' => function($query) {
                            $query->select('id', 'name', 'is_verified');
                        }])
                        ->select('id', 'title', 'description', 'start_date', 'location', 'is_online', 'organizer_id')
                        ->where('start_date', '>=', now())
                        ->orderBy('start_date', 'asc')
                        ->take(2)
                        ->get(),
                ];
            }
            
            \Log::info('All countries content cached', [
                'countries_count' => count($content),
                'cache_key' => 'content.all_countries'
            ]);
            
            return $content;
        });
    }
    
    /**
     * Get content for specific country from cache
     */
    public static function getCountryContent($countrySlug)
    {
        $allContent = self::getAllCountriesContent();
        return $allContent[$countrySlug] ?? [
            'country' => null,
            'news' => collect(),
            'articles' => collect(),
            'events' => collect(),
        ];
    }
    
    /**
     * Get Thailand content (for homepage)
     */
    public static function getThailandContent()
    {
        return self::getCountryContent('thailande');
    }
    
    /**
     * Clear content cache
     */
    public static function clearCache()
    {
        Cache::forget('content.all_countries');
    }
    
    /**
     * Invalidate cache when content is created/updated/deleted
     */
    public static function invalidateContentCache()
    {
        self::clearCache();
        \Log::info('Content cache invalidated due to content change');
    }
}