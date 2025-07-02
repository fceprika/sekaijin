<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\User;
use App\Models\News;
use App\Models\Article;
use App\Models\Event;

class CountryController extends Controller
{
    /**
     * Display country homepage
     */
    public function index(Request $request, $country)
    {
        $countryModel = $request->get('country_model');
        $allCountries = Country::all();
        
        // Get community members from this country
        $communityMembers = User::where('country_residence', $countryModel->name_fr)
            ->take(6)
            ->get();
            
        // Get featured content for homepage
        $featuredNews = News::forCountry($countryModel->id)
            ->published()
            ->featured()
            ->latest()
            ->take(3)
            ->get();
            
        $featuredArticles = Article::forCountry($countryModel->id)
            ->published()
            ->featured()
            ->latest()
            ->take(3)
            ->get();
            
        $upcomingEvents = Event::forCountry($countryModel->id)
            ->published()
            ->upcoming()
            ->orderBy('start_date')
            ->take(3)
            ->get();
        
        return view('country.index', compact('countryModel', 'allCountries', 'communityMembers', 'featuredNews', 'featuredArticles', 'upcomingEvents'));
    }

    /**
     * Display country news section
     */
    public function actualites(Request $request, $country)
    {
        $countryModel = $request->get('country_model');
        
        // Get all news for this country
        $news = News::forCountry($countryModel->id)
            ->published()
            ->with(['author', 'country'])
            ->latest('published_at')
            ->paginate(12);
            
        // Get featured news
        $featuredNews = News::forCountry($countryModel->id)
            ->published()
            ->featured()
            ->latest('published_at')
            ->take(3)
            ->get();
        
        return view('country.actualites', compact('countryModel', 'news', 'featuredNews'));
    }

    /**
     * Display country blog section
     */
    public function blog(Request $request, $country)
    {
        $countryModel = $request->get('country_model');
        
        // Get all articles for this country
        $articles = Article::forCountry($countryModel->id)
            ->published()
            ->with(['author', 'country'])
            ->latest('published_at')
            ->paginate(12);
            
        // Get featured articles
        $featuredArticles = Article::forCountry($countryModel->id)
            ->published()
            ->featured()
            ->latest('published_at')
            ->take(3)
            ->get();
        
        return view('country.blog', compact('countryModel', 'articles', 'featuredArticles'));
    }

    /**
     * Display country community section
     */
    public function communaute(Request $request, $country)
    {
        $countryModel = $request->get('country_model');
        
        // Get community members from this country
        $communityMembers = User::where('country_residence', $countryModel->name_fr)
            ->paginate(12);
        
        return view('country.communaute', compact('countryModel', 'communityMembers'));
    }

    /**
     * Display country events section
     */
    public function evenements(Request $request, $country)
    {
        $countryModel = $request->get('country_model');
        
        // Get upcoming events for this country
        $upcomingEvents = Event::forCountry($countryModel->id)
            ->published()
            ->upcoming()
            ->with(['organizer', 'country'])
            ->orderBy('start_date')
            ->paginate(12);
            
        // Get featured events
        $featuredEvents = Event::forCountry($countryModel->id)
            ->published()
            ->featured()
            ->upcoming()
            ->orderBy('start_date')
            ->take(3)
            ->get();
        
        return view('country.evenements', compact('countryModel', 'upcomingEvents', 'featuredEvents'));
    }

    /**
     * Display individual article
     */
    public function showArticle(Request $request, $country, Article $article)
    {
        $countryModel = $request->get('country_model');
        
        // Verify article belongs to current country
        if ($article->country_id !== $countryModel->id) {
            abort(404);
        }
        
        // Increment views
        $article->increment('views');
        
        // Get related articles
        $relatedArticles = Article::forCountry($countryModel->id)
            ->published()
            ->where('id', '!=', $article->id)
            ->latest('published_at')
            ->take(3)
            ->get();
        
        return view('country.article-show', compact('countryModel', 'article', 'relatedArticles'));
    }

    /**
     * Display individual news
     */
    public function showNews(Request $request, $country, News $news)
    {
        $countryModel = $request->get('country_model');
        
        // Verify news belongs to current country
        if ($news->country_id !== $countryModel->id) {
            abort(404);
        }
        
        // Increment views
        $news->increment('views');
        
        // Get related news
        $relatedNews = News::forCountry($countryModel->id)
            ->published()
            ->where('id', '!=', $news->id)
            ->latest('published_at')
            ->take(3)
            ->get();
        
        return view('country.news-show', compact('countryModel', 'news', 'relatedNews'));
    }

    /**
     * Display individual event
     */
    public function showEvent(Request $request, $country, Event $event)
    {
        $countryModel = $request->get('country_model');
        
        // Verify event belongs to current country
        if ($event->country_id !== $countryModel->id) {
            abort(404);
        }
        
        // Get related events
        $relatedEvents = Event::forCountry($countryModel->id)
            ->published()
            ->upcoming()
            ->where('id', '!=', $event->id)
            ->orderBy('start_date')
            ->take(3)
            ->get();
        
        return view('country.event-show', compact('countryModel', 'event', 'relatedEvents'));
    }
}
