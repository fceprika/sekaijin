<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Country;
use App\Models\Article;
use App\Models\News;
use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class SitemapController extends Controller
{
    /**
     * Generate sitemap.xml
     */
    public function index(): Response
    {
        try {
            $sitemap = Cache::remember('sitemap.xml', 3600, function () {
                return $this->generateSitemap();
            });

            return response($sitemap, 200, [
                'Content-Type' => 'application/xml',
                'Cache-Control' => 'public, max-age=3600'
            ]);
        } catch (\Exception $e) {
            \Log::error('Sitemap generation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Return a minimal sitemap in case of error
            $fallbackSitemap = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
            $fallbackSitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
            $fallbackSitemap .= '    <url><loc>' . url('/') . '</loc><changefreq>daily</changefreq><priority>1.0</priority></url>' . "\n";
            $fallbackSitemap .= '</urlset>';
            
            return response($fallbackSitemap, 200, [
                'Content-Type' => 'application/xml',
                'Cache-Control' => 'public, max-age=300' // Shorter cache for fallback
            ]);
        }
    }

    /**
     * Generate the sitemap XML content
     */
    private function generateSitemap(): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        // Add static pages
        $xml .= $this->addStaticPages();

        // Add countries
        $xml .= $this->addCountries();

        // Add articles
        $xml .= $this->addArticles();

        // Add news
        $xml .= $this->addNews();

        // Add events
        $xml .= $this->addEvents();

        // Add user profiles (public ones)
        $xml .= $this->addUserProfiles();

        $xml .= '</urlset>';

        return $xml;
    }

    /**
     * Add static pages to sitemap
     */
    private function addStaticPages(): string
    {
        $staticPages = [
            [
                'url' => url('/'),
                'lastmod' => Carbon::now()->toISOString(),
                'changefreq' => 'daily',
                'priority' => '1.0'
            ],
            [
                'url' => url('/about'),
                'lastmod' => Carbon::now()->subDays(7)->toISOString(),
                'changefreq' => 'monthly',
                'priority' => '0.8'
            ],
            [
                'url' => url('/contact'),
                'lastmod' => Carbon::now()->subDays(30)->toISOString(),
                'changefreq' => 'monthly',
                'priority' => '0.6'
            ],
            [
                'url' => url('/conditions-utilisation'),
                'lastmod' => Carbon::now()->subDays(90)->toISOString(),
                'changefreq' => 'yearly',
                'priority' => '0.3'
            ],
            [
                'url' => url('/politique-confidentialite'),
                'lastmod' => Carbon::now()->subDays(90)->toISOString(),
                'changefreq' => 'yearly',
                'priority' => '0.3'
            ],
            [
                'url' => url('/mentions-legales'),
                'lastmod' => Carbon::now()->subDays(90)->toISOString(),
                'changefreq' => 'yearly',
                'priority' => '0.3'
            ]
        ];

        $xml = '';
        foreach ($staticPages as $page) {
            $xml .= $this->createUrlEntry($page);
        }

        return $xml;
    }

    /**
     * Add countries to sitemap
     */
    private function addCountries(): string
    {
        $countries = Country::select('slug', 'updated_at')->get();
        $xml = '';

        foreach ($countries as $country) {
            // Main country page
            $xml .= $this->createUrlEntry([
                'url' => url("/{$country->slug}"),
                'lastmod' => $country->updated_at->toISOString(),
                'changefreq' => 'daily',
                'priority' => '0.9'
            ]);

            // Country sub-pages
            $subPages = ['actualites', 'blog', 'communaute', 'evenements'];
            foreach ($subPages as $subPage) {
                $xml .= $this->createUrlEntry([
                    'url' => url("/{$country->slug}/{$subPage}"),
                    'lastmod' => $country->updated_at->toISOString(),
                    'changefreq' => 'daily',
                    'priority' => '0.8'
                ]);
            }
        }

        return $xml;
    }

    /**
     * Add articles to sitemap
     */
    private function addArticles(): string
    {
        $articles = Article::select('id', 'slug', 'updated_at', 'published_at')
            ->where('is_published', true)
            ->with('country')
            ->orderBy('published_at', 'desc')
            ->get();

        $xml = '';
        foreach ($articles as $article) {
            // Use country-based URL structure with slug
            $countrySlug = $article->country ? $article->country->slug : 'global';
            $xml .= $this->createUrlEntry([
                'url' => url("/{$countrySlug}/blog/{$article->slug}"),
                'lastmod' => $article->updated_at->toISOString(),
                'changefreq' => 'monthly',
                'priority' => '0.7'
            ]);
        }

        return $xml;
    }

    /**
     * Add news to sitemap
     */
    private function addNews(): string
    {
        $news = News::select('id', 'slug', 'updated_at', 'published_at')
            ->where('is_published', true)
            ->with('country')
            ->orderBy('published_at', 'desc')
            ->get();

        $xml = '';
        foreach ($news as $newsItem) {
            // Use country-based URL structure with slug
            $countrySlug = $newsItem->country ? $newsItem->country->slug : 'global';
            $xml .= $this->createUrlEntry([
                'url' => url("/{$countrySlug}/actualites/{$newsItem->slug}"),
                'lastmod' => $newsItem->updated_at->toISOString(),
                'changefreq' => 'monthly',
                'priority' => '0.7'
            ]);
        }

        return $xml;
    }

    /**
     * Add events to sitemap
     */
    private function addEvents(): string
    {
        $events = Event::select('id', 'slug', 'updated_at', 'start_date')
            ->where('is_published', true)
            ->where('start_date', '>=', now())
            ->with('country')
            ->orderBy('start_date', 'asc')
            ->get();

        $xml = '';
        foreach ($events as $event) {
            // Use country-based URL structure with slug
            $countrySlug = $event->country ? $event->country->slug : 'global';
            $xml .= $this->createUrlEntry([
                'url' => url("/{$countrySlug}/evenements/{$event->slug}"),
                'lastmod' => $event->updated_at->toISOString(),
                'changefreq' => 'weekly',
                'priority' => '0.6'
            ]);
        }

        return $xml;
    }

    /**
     * Add user profiles to sitemap
     */
    private function addUserProfiles(): string
    {
        try {
            // Only include verified users or users with significant activity
            $users = User::select('name', 'name_slug', 'updated_at')
                ->where(function($query) {
                    $query->where('is_verified', true)
                          ->orWhereHas('articles', function($q) {
                              $q->where('is_published', true);
                          })
                          ->orWhereHas('news', function($q) {
                              $q->where('is_published', true);
                          })
                          ->orWhereHas('events', function($q) {
                              $q->where('is_published', true);
                          });
                })
                ->orderBy('updated_at', 'desc')
                ->limit(1000) // Limit to avoid too many URLs
                ->get();

            $xml = '';
            foreach ($users as $user) {
                // Use slug for URL if available, otherwise use name
                $slug = $user->name_slug ?? $user->name;
                $xml .= $this->createUrlEntry([
                    'url' => url("/membre/{$slug}"),
                    'lastmod' => $user->updated_at->toISOString(),
                    'changefreq' => 'weekly',
                    'priority' => '0.5'
                ]);
            }

            return $xml;
        } catch (\Exception $e) {
            \Log::warning('Failed to add user profiles to sitemap', [
                'error' => $e->getMessage()
            ]);
            return '';
        }
    }

    /**
     * Create a URL entry for the sitemap
     */
    private function createUrlEntry(array $data): string
    {
        $xml = "    <url>\n";
        $xml .= "        <loc>" . htmlspecialchars($data['url']) . "</loc>\n";
        $xml .= "        <lastmod>" . $data['lastmod'] . "</lastmod>\n";
        $xml .= "        <changefreq>" . $data['changefreq'] . "</changefreq>\n";
        $xml .= "        <priority>" . $data['priority'] . "</priority>\n";
        $xml .= "    </url>\n";

        return $xml;
    }

    /**
     * Clear sitemap cache
     */
    public function clearCache()
    {
        Cache::forget('sitemap.xml');
        
        return response()->json([
            'message' => 'Sitemap cache cleared successfully'
        ]);
    }
}