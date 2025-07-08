<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Country;
use App\Models\News;
use App\Models\Article;
use App\Models\Event;
use App\Models\User;
use App\Services\CommunityStatsService;

class HomeController extends Controller
{
    public function index()
    {
        try {
            // Cache configuration - 15 minutes for content, 5 minutes for stats
            $contentCacheTime = 15 * 60; // 15 minutes
            $statsCacheTime = 5 * 60; // 5 minutes
            
            // Get Thailand country with caching (rarely changes)
            $thailand = Cache::remember('country.thailand', 60 * 60, function () {
                return Country::where('slug', 'thailande')->first();
            });
            
            if (!$thailand) {
                throw new \Exception('Thailand country not found');
            }
            
            // Cache Thailand content (news, articles, events)
            $thailandNews = Cache::remember("thailand.news.latest", $contentCacheTime, function () use ($thailand) {
                return News::where('country_id', $thailand->id)
                    ->with(['author' => function($query) {
                        $query->select('id', 'name', 'avatar', 'is_verified');
                    }])
                    ->select('id', 'title', 'slug', 'excerpt', 'content', 'category', 'author_id', 'created_at')
                    ->orderBy('created_at', 'desc')
                    ->take(3)
                    ->get();
            });
                
            $thailandArticles = Cache::remember("thailand.articles.latest", $contentCacheTime, function () use ($thailand) {
                return Article::where('country_id', $thailand->id)
                    ->with(['author' => function($query) {
                        $query->select('id', 'name', 'avatar', 'is_verified');
                    }])
                    ->select('id', 'title', 'slug', 'excerpt', 'content', 'category', 'author_id', 'created_at')
                    ->orderBy('created_at', 'desc')
                    ->take(3)
                    ->get();
            });
                
            $thailandEvents = Cache::remember("thailand.events.upcoming", $contentCacheTime, function () use ($thailand) {
                return Event::where('country_id', $thailand->id)
                    ->with(['organizer' => function($query) {
                        $query->select('id', 'name', 'is_verified');
                    }])
                    ->select('id', 'title', 'description', 'start_date', 'location', 'is_online', 'organizer_id')
                    ->where('start_date', '>=', now())
                    ->orderBy('start_date', 'asc')
                    ->take(2)
                    ->get();
            });
            
            // Get cached community stats
            $communityStats = CommunityStatsService::getCommunityStats();
            
            // Cache recent members (short cache for freshness)
            $recentMembers = Cache::remember('members.recent', $statsCacheTime, function () {
                return User::select('id', 'name', 'avatar', 'country_residence', 'city_residence', 'created_at')
                    ->orderBy('created_at', 'desc')
                    ->take(3)
                    ->get();
            });
            
            return view('home', [
                'thailand' => $thailand,
                'thailandNews' => $thailandNews,
                'thailandArticles' => $thailandArticles,
                'thailandEvents' => $thailandEvents,
                'totalMembers' => $communityStats['totalMembers'],
                'thailandMembers' => $communityStats['thailandMembers'],
                'recentMembers' => $recentMembers,
            ]);
            
        } catch (\Exception $e) {
            \Log::error('HomeController error: ' . $e->getMessage());
            
            // Return cached fallback data or empty collections
            $fallbackData = Cache::get('homepage.fallback', [
                'thailand' => null,
                'thailandNews' => collect(),
                'thailandArticles' => collect(),
                'thailandEvents' => collect(),
                'totalMembers' => 0,
                'thailandMembers' => 0,
                'recentMembers' => collect(),
            ]);
            
            return view('home', $fallbackData);
        }
    }
    
    /**
     * Clear homepage cache (useful for admin actions)
     */
    public function clearCache()
    {
        $cacheKeys = [
            'country.thailand',
            'thailand.news.latest',
            'thailand.articles.latest', 
            'thailand.events.upcoming',
            'members.recent'
        ];
        
        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }
        
        // Clear community stats cache
        CommunityStatsService::clearCache();
        
        return response()->json(['message' => 'Homepage cache cleared successfully']);
    }
}
