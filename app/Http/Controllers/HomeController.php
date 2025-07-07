<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\News;
use App\Models\Article;
use App\Models\Event;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        try {
            // Focus on Thailand only for now
            $thailand = Country::where('slug', 'thailande')->first();
            
            // Get latest content for Thailand
            $thailandNews = $thailand ? News::where('country_id', $thailand->id)
                ->with('author')
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->get() : collect();
                
            $thailandArticles = $thailand ? Article::where('country_id', $thailand->id)
                ->with('author')
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->get() : collect();
                
            $thailandEvents = $thailand ? Event::where('country_id', $thailand->id)
                ->with('organizer')
                ->where('start_date', '>=', now())
                ->orderBy('start_date', 'asc')
                ->take(2)
                ->get() : collect();
            
            // Get community stats
            $totalMembers = User::count();
            $thailandMembers = User::where('country_residence', 'Thaïlande')->count();
            $recentMembers = User::orderBy('created_at', 'desc')->take(3)->get();
            
            return view('home', compact(
                'thailand',
                'thailandNews', 'thailandArticles', 'thailandEvents',
                'totalMembers', 'thailandMembers', 'recentMembers'
            ));
            
        } catch (\Exception $e) {
            \Log::error('HomeController error: ' . $e->getMessage());
            
            // Return view with empty collections on error
            return view('home', [
                'thailand' => null,
                'thailandNews' => collect(),
                'thailandArticles' => collect(),
                'thailandEvents' => collect(),
                'totalMembers' => 0,
                'thailandMembers' => 0,
                'recentMembers' => collect(),
            ]);
        }
    }
}
