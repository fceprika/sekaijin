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
        // Remove allCountries from here as it's now handled by CountryComposer
        
        // Get community members from this country with optimized query
        $communityMembers = User::where('country_residence', $countryModel->name_fr)
            ->select(['id', 'name', 'is_verified', 'role', 'city_residence'])
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
        
        return view('country.index', compact('countryModel', 'communityMembers', 'featuredNews', 'featuredArticles', 'upcomingEvents'));
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
            ->with(['author' => function($query) {
                $query->select('id', 'name', 'avatar', 'is_verified');
            }, 'country'])
            ->latest('published_at')
            ->paginate(12);
            
        // Get featured news
        $featuredNews = News::forCountry($countryModel->id)
            ->published()
            ->featured()
            ->with(['author' => function($query) {
                $query->select('id', 'name', 'avatar', 'is_verified');
            }])
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
            ->with(['author' => function($query) {
                $query->select('id', 'name', 'avatar', 'is_verified');
            }, 'country'])
            ->latest('published_at')
            ->paginate(12);
            
        // Get featured articles
        $featuredArticles = Article::forCountry($countryModel->id)
            ->published()
            ->featured()
            ->with(['author' => function($query) {
                $query->select('id', 'name', 'avatar', 'is_verified');
            }])
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
        
        // Get community members from this country with optimized pagination
        $communityMembers = User::where('country_residence', $countryModel->name_fr)
            ->select(['id', 'name', 'is_verified', 'role', 'city_residence', 'bio', 'created_at'])
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

        // Calculate statistics for sidebar
        $statsThisMonth = Event::forCountry($countryModel->id)
            ->published()
            ->upcoming()
            ->whereMonth('start_date', now()->month)
            ->count();

        $statsFree = Event::forCountry($countryModel->id)
            ->published()
            ->upcoming()
            ->where('price', 0)
            ->count();

        $statsOnline = Event::forCountry($countryModel->id)
            ->published()
            ->upcoming()
            ->where('is_online', true)
            ->count();

        $eventStats = [
            'this_month' => $statsThisMonth,
            'free' => $statsFree,
            'online' => $statsOnline,
        ];
        
        return view('country.evenements', compact('countryModel', 'upcomingEvents', 'featuredEvents', 'eventStats'));
    }

    /**
     * Display individual article
     */
    public function showArticle(Request $request, $country, Article $article)
    {
        try {
            $countryModel = $request->get('country_model');
            
            // Verify article belongs to current country
            if ($article->country_id !== $countryModel->id) {
                abort(404, 'Article non trouvé dans ce pays.');
            }

            // Check authorization to view this article
            $this->authorize('view', $article);
            
            // Load author with avatar
            $article->load(['author' => function($query) {
                $query->select('id', 'name', 'avatar', 'is_verified');
            }]);
            
            // Increment views
            $article->increment('views');
            
            // Get related articles with error handling
            $relatedArticles = Article::forCountry($countryModel->id)
                ->published()
                ->where('id', '!=', $article->id)
                ->with(['author' => function($query) {
                    $query->select('id', 'name', 'avatar', 'is_verified');
                }])
                ->latest('published_at')
                ->take(3)
                ->get();
            
            return view('country.article-show', compact('countryModel', 'article', 'relatedArticles'));
            
        } catch (\Exception $e) {
            \Log::error('Error displaying article: ' . $e->getMessage(), [
                'article_id' => $article->id,
                'country' => $country,
                'user_id' => auth()->id()
            ]);
            
            abort(500, 'Une erreur est survenue lors du chargement de l\'article.');
        }
    }

    /**
     * Display individual news
     */
    public function showNews(Request $request, $country, News $news)
    {
        try {
            $countryModel = $request->get('country_model');
            
            // Verify news belongs to current country
            if ($news->country_id !== $countryModel->id) {
                abort(404, 'Actualité non trouvée dans ce pays.');
            }

            // Check authorization to view this news
            $this->authorize('view', $news);
            
            // Load author with avatar
            $news->load(['author' => function($query) {
                $query->select('id', 'name', 'avatar', 'is_verified');
            }]);
            
            // Increment views
            $news->increment('views');
            
            // Get related news with error handling
            $relatedNews = News::forCountry($countryModel->id)
                ->published()
                ->where('id', '!=', $news->id)
                ->with(['author' => function($query) {
                    $query->select('id', 'name', 'avatar', 'is_verified');
                }])
                ->latest('published_at')
                ->take(3)
                ->get();
            
            return view('country.news-show', compact('countryModel', 'news', 'relatedNews'));
            
        } catch (\Exception $e) {
            \Log::error('Error displaying news: ' . $e->getMessage(), [
                'news_id' => $news->id,
                'country' => $country,
                'user_id' => auth()->id()
            ]);
            
            abort(500, 'Une erreur est survenue lors du chargement de l\'actualité.');
        }
    }

    /**
     * Display individual event
     */
    public function showEvent(Request $request, $country, Event $event)
    {
        try {
            $countryModel = $request->get('country_model');
            
            // Verify event belongs to current country
            if ($event->country_id !== $countryModel->id) {
                abort(404, 'Événement non trouvé dans ce pays.');
            }

            // Check authorization to view this event
            $this->authorize('view', $event);
            
            // Get related events with error handling
            $relatedEvents = Event::forCountry($countryModel->id)
                ->published()
                ->upcoming()
                ->where('id', '!=', $event->id)
                ->orderBy('start_date')
                ->take(3)
                ->get();
            
            return view('country.event-show', compact('countryModel', 'event', 'relatedEvents'));
            
        } catch (\Exception $e) {
            \Log::error('Error displaying event: ' . $e->getMessage(), [
                'event_id' => $event->id,
                'country' => $country,
                'user_id' => auth()->id()
            ]);
            
            abort(500, 'Une erreur est survenue lors du chargement de l\'événement.');
        }
    }
}
