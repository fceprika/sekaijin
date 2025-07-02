<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'full_description',
        'category',
        'image_url',
        'country_id',
        'organizer_id',
        'start_date',
        'end_date',
        'location',
        'address',
        'is_online',
        'online_link',
        'price',
        'max_participants',
        'current_participants',
        'is_published',
        'is_featured',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_online' => 'boolean',
        'is_published' => 'boolean',
        'is_featured' => 'boolean',
        'price' => 'decimal:2',
    ];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get the country that owns the event
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Get the organizer of the event
     */
    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    /**
     * Scope for published events
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope for featured events
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope for upcoming events
     */
    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>', now());
    }

    /**
     * Scope for a specific country
     */
    public function scopeForCountry($query, $countryId)
    {
        return $query->where('country_id', $countryId);
    }

    /**
     * Check if event is free
     */
    public function isFree()
    {
        return $this->price == 0;
    }

    /**
     * Check if event has available spots
     */
    public function hasAvailableSpots()
    {
        if (!$this->max_participants) return true;
        return $this->current_participants < $this->max_participants;
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute()
    {
        return $this->price == 0 ? 'Gratuit' : $this->price . '€';
    }
}
