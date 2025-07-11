<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'description',
        'type',
        'price',
        'currency',
        'country',
        'city',
        'address',
        'images',
        'status',
        'refusal_reason',
        'expiration_date',
        'views'
    ];

    protected $casts = [
        'images' => 'array',
        'price' => 'decimal:2',
        'expiration_date' => 'date',
        'views' => 'integer'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($announcement) {
            if (empty($announcement->slug)) {
                $announcement->slug = static::generateUniqueSlug($announcement->title);
            }
        });
    }

    protected static function generateUniqueSlug($title)
    {
        $slug = Str::slug($title);
        $count = static::whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();
        return $count ? "{$slug}-{$count}" : $slug;
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                    ->where(function ($q) {
                        $q->whereNull('expiration_date')
                          ->orWhere('expiration_date', '>=', now());
                    });
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeRefused($query)
    {
        return $query->where('status', 'refused');
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeInCountry($query, $country)
    {
        return $query->where('country', $country);
    }

    public function scopeInCity($query, $city)
    {
        return $query->where('city', $city);
    }

    public function getFormattedPriceAttribute()
    {
        if (!$this->price) {
            return 'Gratuit';
        }
        return number_format($this->price, 2, ',', ' ') . ' ' . $this->currency;
    }

    public function getTypeDisplayAttribute()
    {
        $types = [
            'vente' => 'Vente',
            'location' => 'Location',
            'colocation' => 'Colocation',
            'service' => 'Service'
        ];
        return $types[$this->type] ?? $this->type;
    }

    public function getStatusDisplayAttribute()
    {
        $statuses = [
            'pending' => 'En attente',
            'active' => 'Active',
            'refused' => 'Refusée'
        ];
        return $statuses[$this->status] ?? $this->status;
    }

    public function isActive()
    {
        return $this->status === 'active' && 
               (!$this->expiration_date || $this->expiration_date->isFuture());
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isRefused()
    {
        return $this->status === 'refused';
    }

    public function canBeEditedBy(User $user)
    {
        return $user->id === $this->user_id || $user->isAdmin();
    }

    public function incrementViews()
    {
        $this->increment('views');
    }
}
