<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_fr',
        'slug',
        'emoji',
        'description',
    ];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get users living in this country (using proper foreign key).
     */
    public function users()
    {
        return $this->hasMany(User::class, 'country_id');
    }

    /**
     * Get users living in this country (legacy method for backward compatibility).
     *
     * @deprecated Use users() instead
     */
    public function usersLegacy()
    {
        return $this->hasMany(User::class, 'country_residence', 'name_fr');
    }

    /**
     * Get the banner image path for this country.
     */
    public function getBannerImage(): ?string
    {
        $bannerMapping = [
            'thailande' => 'images/banners/thailand-banner.jpg',
            'vietnam' => 'images/banners/vietnam_banner.jpg',
            'japon' => 'images/banners/banner_japan.jpg',
            'chine' => 'images/banners/chine_banner.webp',
        ];

        return $bannerMapping[$this->slug] ?? null;
    }
}
