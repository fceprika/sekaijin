<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\User;
use App\Models\News;
use App\Models\Article;
use App\Models\Event;
use App\Models\Announcement;
use App\Services\SeoService;
use Illuminate\Support\Facades\Auth;

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
            
        // Get latest content for homepage (not just featured)
        $featuredNews = News::forCountry($countryModel->id)
            ->published()
            ->latest()
            ->take(3)
            ->get();
            
        $featuredArticles = Article::forCountry($countryModel->id)
            ->published()
            ->latest()
            ->take(3)
            ->get();
            
        $upcomingEvents = Event::forCountry($countryModel->id)
            ->published()
            ->upcoming()
            ->orderBy('start_date')
            ->take(3)
            ->get();
            
        // Get latest announcements for this country
        $latestAnnouncements = Announcement::active()
            ->inCountry($countryModel->name_fr)
            ->with(['user' => function($query) {
                $query->select('id', 'name', 'avatar', 'is_verified');
            }])
            ->latest()
            ->take(3)
            ->get();
        
        // Generate SEO data for country page
        $seoService = new SeoService();
        $seoData = $seoService->generateSeoData('country', $countryModel);
        $structuredData = $seoService->generateStructuredData('country', $countryModel);
        
        return view('country.index', compact('countryModel', 'communityMembers', 'featuredNews', 'featuredArticles', 'upcomingEvents', 'latestAnnouncements', 'seoData', 'structuredData'));
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
            ->select(['id', 'name', 'avatar', 'is_verified', 'role', 'city_residence', 'bio', 'created_at'])
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
            
            // Generate SEO data for article page
            $seoService = new SeoService();
            $seoData = $seoService->generateSeoData('article', $article);
            $structuredData = $seoService->generateStructuredData('article', $article);
            
            // Make country data available under expected variable names
            $currentCountry = $countryModel;
            $country = $countryModel;
            
            return view('country.article-show', compact('countryModel', 'currentCountry', 'country', 'article', 'relatedArticles', 'seoData', 'structuredData'));
            
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
            
            // Generate SEO data for news page
            $seoService = new SeoService();
            $seoData = $seoService->generateSeoData('news', $news);
            $structuredData = $seoService->generateStructuredData('news', $news);
            
            // Make country data available under expected variable names
            $currentCountry = $countryModel;
            $country = $countryModel;
            
            return view('country.news-show', compact('countryModel', 'currentCountry', 'country', 'news', 'relatedNews', 'seoData', 'structuredData'));
            
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
            
            // Load organizer with avatar
            $event->load(['organizer' => function($query) {
                $query->select('id', 'name', 'avatar', 'is_verified');
            }]);
            
            // Get related events with error handling
            $relatedEvents = Event::forCountry($countryModel->id)
                ->published()
                ->upcoming()
                ->where('id', '!=', $event->id)
                ->with(['organizer' => function($query) {
                    $query->select('id', 'name', 'avatar', 'is_verified');
                }])
                ->orderBy('start_date')
                ->take(3)
                ->get();
            
            // Generate SEO data for event page
            $seoService = new SeoService();
            $seoData = $seoService->generateSeoData('event', $event);
            $structuredData = $seoService->generateStructuredData('event', $event);
            
            // Make country data available under expected variable names
            $currentCountry = $countryModel;
            $country = $countryModel;
            
            return view('country.event-show', compact('countryModel', 'currentCountry', 'country', 'event', 'relatedEvents', 'seoData', 'structuredData'));
            
        } catch (\Exception $e) {
            \Log::error('Error displaying event: ' . $e->getMessage(), [
                'event_id' => $event->id,
                'country' => $country,
                'user_id' => auth()->id()
            ]);
            
            abort(500, 'Une erreur est survenue lors du chargement de l\'événement.');
        }
    }

    /**
     * Display announcements for a specific country
     */
    public function annonces(Request $request, $country)
    {
        $countryModel = $request->get('country_model');
        
        $query = Announcement::active()
            ->inCountry($countryModel->name_fr)
            ->with('user');

        // Filtres
        if ($request->filled('type')) {
            $query->ofType($request->type);
        }

        if ($request->filled('city')) {
            $query->inCity($request->city);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Tri
        $sort = $request->get('sort', 'recent');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'views':
                $query->orderBy('views', 'desc');
                break;
            default:
                $query->latest();
        }

        $announcements = $query->paginate(12);

        // Obtenir les villes pour ce pays
        $cities = Announcement::active()
            ->inCountry($countryModel->name_fr)
            ->distinct()
            ->pluck('city')
            ->sort()
            ->values();

        return view('country.annonces', compact('countryModel', 'announcements', 'cities'));
    }

    /**
     * Show form for creating announcement in specific country
     */
    public function createAnnouncement(Request $request, $country)
    {
        $countryModel = $request->get('country_model');
        
        return view('country.announcements.create', compact('countryModel'));
    }

    /**
     * Display individual announcement
     */
    public function showAnnouncement(Request $request, $country, Announcement $announcement)
    {
        $countryModel = $request->get('country_model');

        // Vérifier que l'annonce appartient bien à ce pays
        if ($announcement->country !== $countryModel->name_fr) {
            abort(404);
        }

        // Vérifier si l'annonce est visible
        if (!$announcement->isActive() && 
            (!Auth::check() || !$announcement->canBeEditedBy(Auth::user()))) {
            abort(404);
        }

        $announcement->incrementViews();
        $announcement->load('user');

        // Annonces similaires dans le même pays
        $similarAnnouncements = Announcement::active()
            ->where('id', '!=', $announcement->id)
            ->where('type', $announcement->type)
            ->where('country', $announcement->country)
            ->limit(4)
            ->get();

        return view('country.announcement-show', compact('countryModel', 'announcement', 'similarAnnouncements'));
    }
}
