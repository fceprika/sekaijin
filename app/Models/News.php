<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'category',
        'image_url',
        'country_id',
        'author_id',
        'is_featured',
        'is_published',
        'published_at',
        'views',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    /**
     * Get the country that owns the news
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Get the author of the news
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Scope for published news
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true)
                     ->whereNotNull('published_at')
                     ->where('published_at', '<=', now());
    }

    /**
     * Scope for featured news
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope for a specific country
     */
    public function scopeForCountry($query, $countryId)
    {
        return $query->where('country_id', $countryId);
    }

    /**
     * Generate a unique slug from title
     */
    public static function generateSlug($title, $id = null)
    {
        $baseSlug = \Illuminate\Support\Str::slug($title);
        $slug = $baseSlug;
        $counter = 1;
        
        while (self::where('slug', $slug)->when($id, function($query, $id) {
            return $query->where('id', '!=', $id);
        })->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Boot the model events
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($news) {
            if (empty($news->slug)) {
                $news->slug = self::generateSlug($news->title);
            }
        });
        
        static::updating(function ($news) {
            // Regenerate slug if title changed OR if slug is empty OR if force update is requested
            if ($news->isDirty('title') && (empty($news->slug) || $news->shouldForceSlugUpdate())) {
                $news->slug = self::generateSlug($news->title, $news->id);
            }
        });
        
        // Invalidate content cache when news is created, updated, or deleted
        static::created(function ($news) {
            \App\Services\ContentCacheService::invalidateContentCache();
        });
        
        static::updated(function ($news) {
            \App\Services\ContentCacheService::invalidateContentCache();
        });
        
        static::deleted(function ($news) {
            \App\Services\ContentCacheService::invalidateContentCache();
        });
    }
    
    /**
     * Temporary flag for forcing slug update (not persisted to DB)
     */
    private $forceSlugUpdateFlag = false;
    
    /**
     * Force slug regeneration on next save
     */
    public function forceSlugUpdate()
    {
        $this->forceSlugUpdateFlag = true;
        return $this;
    }
    
    /**
     * Check if force slug update is requested
     */
    public function shouldForceSlugUpdate()
    {
        return $this->forceSlugUpdateFlag;
    }

    /**
     * Get users who favorited this news
     */
    public function favoritedBy()
    {
        return $this->morphToMany(User::class, 'favoritable', 'favorites');
    }

    /**
     * Get favorites for this news
     */
    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favoritable');
    }

    /**
     * Check if this news is favorited by a specific user
     */
    public function isFavoritedBy(?\App\Models\User $user): bool
    {
        if (!$user) return false;
        
        return $this->favorites()
            ->where('user_id', $user->id)
            ->exists();
    }
}
