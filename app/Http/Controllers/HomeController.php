<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\News;
use App\Models\Article;
use App\Models\Event;

class HomeController extends Controller
{
    public function index()
    {
        try {
            // Get countries in a single query for better performance
            $countries = Country::whereIn('slug', ['thailande', 'japon'])->get()->keyBy('slug');
            $thailand = $countries->get('thailande');
            $japan = $countries->get('japon');
        
            // Get latest content for Thailand
            $thailandNews = $thailand ? News::where('country_id', $thailand->id)
                ->with('author')
                ->orderBy('created_at', 'desc')
                ->take(2)
                ->get() : collect();
                
            $thailandArticles = $thailand ? Article::where('country_id', $thailand->id)
                ->with('author')
                ->orderBy('created_at', 'desc')
                ->take(2)
                ->get() : collect();
                
            $thailandEvents = $thailand ? Event::where('country_id', $thailand->id)
                ->with('organizer')
                ->where('start_date', '>=', now())
                ->orderBy('start_date', 'asc')
                ->take(1)
                ->get() : collect();
            
            // Get latest content for Japan
            $japanNews = $japan ? News::where('country_id', $japan->id)
                ->with('author')
                ->orderBy('created_at', 'desc')
                ->take(2)
                ->get() : collect();
                
            $japanArticles = $japan ? Article::where('country_id', $japan->id)
                ->with('author')
                ->orderBy('created_at', 'desc')
                ->take(2)
                ->get() : collect();
                
            $japanEvents = $japan ? Event::where('country_id', $japan->id)
                ->with('organizer')
                ->where('start_date', '>=', now())
                ->orderBy('start_date', 'asc')
                ->take(1)
                ->get() : collect();
            
            return view('home', compact(
                'thailand', 'japan',
                'thailandNews', 'thailandArticles', 'thailandEvents',
                'japanNews', 'japanArticles', 'japanEvents'
            ));
            
        } catch (\Exception $e) {
            \Log::error('HomeController error: ' . $e->getMessage());
            
            // Return view with empty collections on error
            return view('home', [
                'thailand' => null,
                'japan' => null,
                'thailandNews' => collect(),
                'thailandArticles' => collect(),
                'thailandEvents' => collect(),
                'japanNews' => collect(),
                'japanArticles' => collect(),
                'japanEvents' => collect(),
            ]);
        }
    }
}
