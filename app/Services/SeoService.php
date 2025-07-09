<?php

namespace App\Services;

use App\Models\Article;
use App\Models\News;
use App\Models\Event;
use App\Models\Country;
use App\Models\User;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class SeoService
{
    /**
     * Default SEO configuration
     */
    private array $defaultSeo = [
        'title' => 'Sekaijin - Communauté des expatriés français',
        'description' => 'Rejoignez la plus grande communauté d\'expatriés français. Connectez-vous avec des compatriotes, découvrez des conseils pratiques et vivez pleinement votre expérience à l\'étranger.',
        'keywords' => 'expatriés français, communauté française, expat, vivre à l\'étranger, français à l\'étranger, expatriation',
        'image' => '/images/sekaijin_logo.png',
        'type' => 'website',
        'locale' => 'fr_FR',
        'site_name' => 'Sekaijin'
    ];

    /**
     * Generate SEO data for any page
     */
    public function generateSeoData(string $type, $model = null, array $customData = []): array
    {
        $seo = $this->defaultSeo;
        
        switch ($type) {
            case 'home':
                $seo = $this->getHomeSeoData();
                break;
            case 'article':
                $seo = $this->getArticleSeoData($model);
                break;
            case 'news':
                $seo = $this->getNewsSeoData($model);
                break;
            case 'event':
                $seo = $this->getEventSeoData($model);
                break;
            case 'country':
                $seo = $this->getCountrySeoData($model);
                break;
            case 'profile':
                $seo = $this->getProfileSeoData($model);
                break;
            case 'about':
                $seo = $this->getAboutSeoData();
                break;
            case 'contact':
                $seo = $this->getContactSeoData();
                break;
        }

        // Merge custom data
        $seo = array_merge($seo, $customData);

        // Add canonical URL
        $seo['canonical'] = $seo['canonical'] ?? URL::current();

        // Ensure proper URL format for image
        if (isset($seo['image']) && !str_starts_with($seo['image'], 'http')) {
            $seo['image'] = URL::to($seo['image']);
        }

        return $seo;
    }

    /**
     * Home page SEO data
     */
    private function getHomeSeoData(): array
    {
        return [
            'title' => 'Sekaijin - La communauté des expatriés français dans le monde',
            'description' => 'Rejoignez plus de 25 000 expatriés français dans 150 pays. Découvrez des conseils pratiques, connectez-vous avec des compatriotes et vivez pleinement votre expérience à l\'étranger.',
            'keywords' => 'expatriés français, communauté française, expat, vivre à l\'étranger, français à l\'étranger, expatriation, communauté internationale',
            'canonical' => URL::to('/'),
            'type' => 'website'
        ];
    }

    /**
     * Article SEO data
     */
    private function getArticleSeoData(Article $article): array
    {
        return [
            'title' => $article->title . ' - Sekaijin',
            'description' => $article->excerpt ?: Str::limit(strip_tags($article->content), 160),
            'keywords' => $this->generateKeywords($article->title, $article->category, $article->country->name_fr ?? ''),
            'canonical' => URL::to("/article/{$article->id}"),
            'type' => 'article',
            'article' => [
                'published_time' => $article->published_at?->toISOString(),
                'modified_time' => $article->updated_at?->toISOString() ?? now()->toISOString(),
                'author' => $article->author->name ?? 'Sekaijin',
                'section' => $this->getCategoryDisplayName($article->category ?? null),
                'tag' => [$article->category, $article->country->name_fr ?? '']
            ]
        ];
    }

    /**
     * News SEO data
     */
    private function getNewsSeoData(News $news): array
    {
        return [
            'title' => $news->title . ' - Actualités Sekaijin',
            'description' => $news->excerpt ?: Str::limit(strip_tags($news->content), 160),
            'keywords' => $this->generateKeywords($news->title, $news->category, $news->country->name_fr ?? ''),
            'canonical' => URL::to("/news/{$news->id}"),
            'type' => 'article',
            'article' => [
                'published_time' => $news->published_at?->toISOString(),
                'modified_time' => $news->updated_at?->toISOString() ?? now()->toISOString(),
                'author' => $news->author->name ?? 'Sekaijin',
                'section' => 'Actualités',
                'tag' => [$news->category, $news->country->name_fr ?? '']
            ]
        ];
    }

    /**
     * Event SEO data
     */
    private function getEventSeoData(Event $event): array
    {
        return [
            'title' => $event->title . ' - Événements Sekaijin',
            'description' => $event->description ?: Str::limit(strip_tags($event->full_description), 160),
            'keywords' => $this->generateKeywords($event->title, $event->category, $event->country->name_fr ?? ''),
            'canonical' => URL::to("/event/{$event->id}"),
            'type' => 'article',
            'event' => [
                'start_time' => $event->start_date?->toISOString(),
                'end_time' => $event->end_date?->toISOString(),
                'location' => $event->location,
                'organizer' => $event->organizer->name ?? 'Sekaijin'
            ]
        ];
    }

    /**
     * Country SEO data
     */
    private function getCountrySeoData(Country $country): array
    {
        return [
            'title' => "Communauté française en {$country->name_fr} - Sekaijin",
            'description' => "Découvrez la communauté française en {$country->name_fr}. Actualités, conseils pratiques, événements et témoignages d'expatriés français.",
            'keywords' => "expatriés français {$country->name_fr}, français en {$country->name_fr}, communauté française {$country->name_fr}, vivre en {$country->name_fr}",
            'canonical' => URL::to("/{$country->slug}"),
            'type' => 'website'
        ];
    }

    /**
     * Profile SEO data
     */
    private function getProfileSeoData(User $user): array
    {
        return [
            'title' => "Profil de {$user->name} - Sekaijin",
            'description' => $user->bio ? Str::limit($user->bio, 160) : "Découvrez le profil de {$user->name}, membre de la communauté Sekaijin.",
            'keywords' => "profil {$user->name}, expatrié français, membre Sekaijin",
            'canonical' => URL::to("/membre/{$user->name}"),
            'type' => 'profile',
            'image' => $user->avatar ? URL::to("/storage/{$user->avatar}") : '/images/default-avatar.png'
        ];
    }

    /**
     * About page SEO data
     */
    private function getAboutSeoData(): array
    {
        return [
            'title' => 'À propos - Sekaijin, la communauté des expatriés français',
            'description' => 'Découvrez Sekaijin, la plateforme qui connecte les expatriés français du monde entier. Notre mission, nos valeurs et notre communauté.',
            'keywords' => 'à propos Sekaijin, communauté expatriés français, mission, valeurs, équipe',
            'canonical' => URL::to('/about'),
            'type' => 'website'
        ];
    }

    /**
     * Contact page SEO data
     */
    private function getContactSeoData(): array
    {
        return [
            'title' => 'Contact - Sekaijin',
            'description' => 'Contactez l\'équipe Sekaijin. Nous sommes là pour vous accompagner dans votre expérience d\'expatriation.',
            'keywords' => 'contact Sekaijin, support, aide, équipe',
            'canonical' => URL::to('/contact'),
            'type' => 'website'
        ];
    }

    /**
     * Generate keywords from content
     */
    private function generateKeywords(?string $title, ?string $category, ?string $country): string
    {
        $keywords = [];
        
        // Add base keywords
        $keywords[] = 'expatriés français';
        $keywords[] = 'communauté française';
        
        // Add category
        if ($category) {
            $keywords[] = $this->getCategoryDisplayName($category);
        }
        
        // Add country
        if ($country) {
            $keywords[] = "français en {$country}";
            $keywords[] = "expatriés {$country}";
        }
        
        // Add title words (significant ones)
        if ($title) {
            $titleWords = str_word_count(strtolower($title), 1, 'àáâãäåæçèéêëìíîïñòóôõöøùúûüýÿ');
            $significantWords = array_filter($titleWords, function($word) {
                return strlen($word) > 3 && !in_array($word, ['dans', 'pour', 'avec', 'cette', 'tout', 'tous', 'leur', 'leurs', 'mais', 'plus', 'très', 'bien', 'après', 'avant']);
            });
            
            $keywords = array_merge($keywords, array_slice($significantWords, 0, 3));
        }
        
        return implode(', ', array_unique($keywords));
    }

    /**
     * Get category display name
     */
    private function getCategoryDisplayName(?string $category): string
    {
        $categories = [
            'témoignage' => 'Témoignages',
            'guide-pratique' => 'Guide Pratique',
            'travail' => 'Travail',
            'lifestyle' => 'Style de Vie',
            'cuisine' => 'Cuisine',
            'administrative' => 'Administratif',
            'vie-pratique' => 'Vie Pratique',
            'culture' => 'Culture',
            'economie' => 'Économie'
        ];
        
        if (!$category) {
            return 'Général';
        }
        
        return $categories[$category] ?? ucfirst($category);
    }

    /**
     * Generate JSON-LD structured data
     */
    public function generateStructuredData(string $type, $model = null): array
    {
        $baseData = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => 'Sekaijin',
            'url' => URL::to('/'),
            'logo' => URL::to('/images/sekaijin_logo.png'),
            'sameAs' => [
                'https://facebook.com/sekaijin',
                'https://twitter.com/sekaijin',
                'https://instagram.com/sekaijin'
            ]
        ];

        switch ($type) {
            case 'article':
                return $this->getArticleStructuredData($model);
            case 'news':
                return $this->getNewsStructuredData($model);
            case 'event':
                return $this->getEventStructuredData($model);
            case 'profile':
                return $this->getProfileStructuredData($model);
            default:
                return $baseData;
        }
    }

    /**
     * Article structured data
     */
    private function getArticleStructuredData(Article $article): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $article->title,
            'description' => $article->excerpt ?: Str::limit(strip_tags($article->content), 160),
            'image' => URL::to('/images/sekaijin_logo.png'),
            'author' => [
                '@type' => 'Person',
                'name' => $article->author->name ?? 'Sekaijin'
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => 'Sekaijin',
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => URL::to('/images/sekaijin_logo.png')
                ]
            ],
            'datePublished' => $article->published_at?->toISOString(),
            'dateModified' => $article->updated_at?->toISOString() ?? now()->toISOString(),
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => URL::to("/article/{$article->id}")
            ]
        ];
    }

    /**
     * News structured data
     */
    private function getNewsStructuredData(News $news): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'NewsArticle',
            'headline' => $news->title,
            'description' => $news->excerpt ?: Str::limit(strip_tags($news->content), 160),
            'image' => URL::to('/images/sekaijin_logo.png'),
            'author' => [
                '@type' => 'Person',
                'name' => $news->author->name ?? 'Sekaijin'
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => 'Sekaijin',
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => URL::to('/images/sekaijin_logo.png')
                ]
            ],
            'datePublished' => $news->published_at?->toISOString(),
            'dateModified' => $news->updated_at?->toISOString() ?? now()->toISOString(),
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => URL::to("/news/{$news->id}")
            ]
        ];
    }

    /**
     * Event structured data
     */
    private function getEventStructuredData(Event $event): array
    {
        $data = [
            '@context' => 'https://schema.org',
            '@type' => 'Event',
            'name' => $event->title,
            'description' => $event->description ?: Str::limit(strip_tags($event->full_description), 160),
            'startDate' => $event->start_date?->toISOString(),
            'endDate' => $event->end_date?->toISOString(),
            'organizer' => [
                '@type' => 'Person',
                'name' => $event->organizer->name ?? 'Sekaijin'
            ],
            'url' => URL::to("/event/{$event->id}")
        ];

        if ($event->location && !$event->is_online) {
            $data['location'] = [
                '@type' => 'Place',
                'name' => $event->location,
                'address' => $event->address
            ];
        } elseif ($event->is_online) {
            $data['location'] = [
                '@type' => 'VirtualLocation',
                'url' => $event->online_link
            ];
        }

        if ($event->price > 0) {
            $data['offers'] = [
                '@type' => 'Offer',
                'price' => $event->price,
                'priceCurrency' => 'EUR'
            ];
        }

        return $data;
    }

    /**
     * Profile structured data
     */
    private function getProfileStructuredData(User $user): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Person',
            'name' => $user->name,
            'description' => $user->bio,
            'image' => $user->avatar ? URL::to("/storage/{$user->avatar}") : URL::to('/images/default-avatar.png'),
            'url' => URL::to("/membre/{$user->name}")
        ];
    }
}