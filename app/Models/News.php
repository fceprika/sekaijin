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
        'summary',
        'content',
        'thumbnail_path',
        'author_id',
        'status',
        'published_at',
        'tags',
        // Legacy fields (pour compatibilité existante)
        'excerpt',
        'category',
        'image_url',
        'country_id',
        'is_featured',
        'is_published',
        'views',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'tags' => 'array',
        // Legacy casts (pour compatibilité existante)
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
    ];

    /**
     * Get the country that owns the news.
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Get the author of the news.
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Scope for published news (nouvelle version pour API).
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /**
     * Scope for legacy published news (ancienne version pour compatibilité).
     */
    public function scopeLegacyPublished($query)
    {
        return $query->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /**
     * Scope for featured news.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope for a specific country.
     */
    public function scopeForCountry($query, $countryId)
    {
        return $query->where('country_id', $countryId);
    }

    /**
     * Generate a unique slug from title.
     */
    public static function generateSlug($title, $id = null)
    {
        $baseSlug = \Illuminate\Support\Str::slug($title);
        $slug = $baseSlug;
        $counter = 1;

        while (self::where('slug', $slug)->when($id, function ($query, $id) {
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
     * Boot the model events.
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
     * Temporary flag for forcing slug update (not persisted to DB).
     */
    private $forceSlugUpdateFlag = false;

    /**
     * Force slug regeneration on next save.
     */
    public function forceSlugUpdate()
    {
        $this->forceSlugUpdateFlag = true;

        return $this;
    }

    /**
     * Check if force slug update is requested.
     */
    public function shouldForceSlugUpdate()
    {
        return $this->forceSlugUpdateFlag;
    }

    /**
     * Get users who favorited this news.
     */
    public function favoritedBy()
    {
        return $this->morphToMany(User::class, 'favoritable', 'favorites');
    }

    /**
     * Get favorites for this news.
     */
    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favoritable');
    }

    /**
     * Check if this news is favorited by a specific user.
     */
    public function isFavoritedBy(?\App\Models\User $user): bool
    {
        if (! $user) {
            return false;
        }

        return $this->favorites()
            ->where('user_id', $user->id)
            ->exists();
    }

    /**
     * Get the thumbnail URL for API responses.
     */
    public function getThumbnailUrlAttribute(): ?string
    {
        if ($this->thumbnail_path) {
            return asset('storage/news_thumbnails/' . $this->thumbnail_path);
        }

        return null;
    }

    /**
     * Check if this news has duplicate title.
     */
    public static function hasDuplicateTitle(string $title, ?int $excludeId = null): bool
    {
        $query = self::where('title', $title);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    /**
     * Mark as published and set published_at timestamp.
     */
    public function markAsPublished(): self
    {
        $this->update([
            'status' => 'published',
            'published_at' => now(),
        ]);

        return $this;
    }

    /**
     * Mark as draft and clear published_at timestamp.
     */
    public function markAsDraft(): self
    {
        $this->update([
            'status' => 'draft',
            'published_at' => null,
        ]);

        return $this;
    }
}
