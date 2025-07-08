<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
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
        'likes',
        'reading_time',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get the country that owns the article
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Get the author of the article
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Scope for published articles
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope for featured articles
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
     * Get reading time in human readable format
     */
    public function getReadingTimeAttribute($value)
    {
        if (!$value) return null;
        return $value . ' min de lecture';
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
     * Boot the model events
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($article) {
            if (empty($article->slug)) {
                $article->slug = self::generateSlug($article->title);
            }
        });
        
        static::updating(function ($article) {
            if ($article->isDirty('title') && empty($article->slug)) {
                $article->slug = self::generateSlug($article->title, $article->id);
            }
        });
    }
}
