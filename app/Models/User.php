<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // Route constants
    public const ROUTE_PUBLIC_PROFILE = 'public.profile';

    protected $fillable = [
        'name',
        'name_slug',
        'first_name',
        'last_name',
        'email',
        'avatar',
        'role',
        'birth_date',
        'phone',
        'country_residence',
        'destination_country',
        'country_id',
        'city_residence',
        'bio',
        'youtube_username',
        'instagram_username',
        'tiktok_username',
        'linkedin_username',
        'twitter_username',
        'facebook_username',
        'telegram_username',
        'password',
        'is_verified',
        'last_login',
        'is_visible_on_map',
        'latitude',
        'longitude',
        'city_detected',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'birth_date' => 'date',
        'last_login' => 'datetime',
        'is_verified' => 'boolean',
        'password' => 'hashed',
        'is_visible_on_map' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    /**
     * Available user roles
     */
    public const ROLES = [
        'free' => 'free',
        'premium' => 'premium',
        'ambassador' => 'ambassador',
        'admin' => 'admin',
    ];

    /**
     * Check if user has a specific role
     */
    public function isRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->isRole('admin');
    }

    /**
     * Check if user is premium
     */
    public function isPremium(): bool
    {
        return $this->isRole('premium');
    }

    /**
     * Check if user is ambassador
     */
    public function isAmbassador(): bool
    {
        return $this->isRole('ambassador');
    }

    /**
     * Check if user is free member
     */
    public function isFree(): bool
    {
        return $this->isRole('free');
    }

    /**
     * Get user role display name
     */
    public function getRoleDisplayName(): string
    {
        return match($this->role) {
            'free' => 'Membre',
            'premium' => 'Membre Premium',
            'ambassador' => 'Ambassadeur Sekaijin',
            'admin' => 'Administrateur',
            default => 'Membre',
        };
    }

    /**
     * Get the country relationship using proper foreign key
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Check if user has enabled location sharing
     */
    public function hasLocationSharing(): bool
    {
        return $this->is_visible_on_map && $this->latitude && $this->longitude;
    }

    /**
     * Get display location (city, country) - always in French with Latin characters
     */
    public function getDisplayLocation(): string
    {
        // Utiliser le nom français du pays via la relation
        $countryName = $this->country ? $this->country->name_fr : $this->country_residence;
        
        // Privilégier city_residence (saisi manuellement) sur city_detected (auto-détecté)
        // Et vérifier que le nom contient des caractères latins
        $cityName = null;
        
        if ($this->city_residence && $this->isLatinText($this->city_residence)) {
            $cityName = $this->city_residence;
        } elseif ($this->city_detected && $this->isLatinText($this->city_detected)) {
            $cityName = $this->city_detected;
        } elseif ($this->city_residence) {
            $cityName = $this->city_residence; // Fallback même si non-latin
        } elseif ($this->city_detected) {
            $cityName = $this->city_detected; // Fallback même si non-latin
        }
        
        if ($cityName && $countryName) {
            return "{$cityName}, {$countryName}";
        }
        
        return $countryName ?? 'Non renseigné';
    }
    
    /**
     * Check if text contains only Latin characters (including French accents)
     */
    private function isLatinText(string $text): bool
    {
        // Use Unicode property to match Latin characters and common punctuation
        return preg_match('/^[\p{Latin}\s\-\.,\']+$/u', $text);
    }

    /**
     * Update user location with privacy protection (randomize within ~10km radius)
     */
    public function updateLocation(float $latitude, float $longitude, ?string $city = null): void
    {
        $randomizedCoords = $this->randomizeCoordinates($latitude, $longitude);
        
        // Ne stocker la ville que si elle contient des caractères latins
        $cityToStore = null;
        if ($city && $this->isLatinText($city)) {
            $cityToStore = $city;
        }
        
        $this->update([
            'latitude' => $randomizedCoords['latitude'],
            'longitude' => $randomizedCoords['longitude'],
            'city_detected' => $cityToStore,
        ]);
    }

    /**
     * Randomize coordinates within approximately 10km radius for privacy
     */
    private function randomizeCoordinates(float $latitude, float $longitude): array
    {
        $radius = 0.09; // Approximately 10km in decimal degrees
        
        $randomLat = $latitude + (mt_rand(-100, 100) / 100) * $radius;
        $randomLng = $longitude + (mt_rand(-100, 100) / 100) * $radius;
        
        return [
            'latitude' => round($randomLat, 6),
            'longitude' => round($randomLng, 6),
        ];
    }

    /**
     * Get avatar URL or default avatar
     */
    public function getAvatarUrl(): string
    {
        if ($this->avatar && file_exists(public_path('storage/avatars/' . $this->avatar))) {
            return asset('storage/avatars/' . $this->avatar);
        }
        
        // Default avatar - using a placeholder service
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=3B82F6&color=fff&size=200';
    }

    /**
     * Get the URL-safe slug for this user
     */
    public function getSlug(): string
    {
        return $this->name_slug ?? strtolower($this->name);
    }

    /**
     * Get the public profile URL for this user
     */
    public function getPublicProfileUrl(): string
    {
        return route(self::ROUTE_PUBLIC_PROFILE, $this->getSlug());
    }

    /**
     * Generate URL-safe slug from username
     */
    public static function generateSlug(string $name): string
    {
        // Clean the name: remove invalid characters, trim, convert to lowercase
        $cleaned = trim($name);
        $cleaned = preg_replace('/[^a-zA-Z0-9._-]/', '', $cleaned);
        $cleaned = strtolower($cleaned);
        
        // Ensure slug is not empty after cleaning
        if (empty($cleaned)) {
            throw new \InvalidArgumentException('Username cannot be converted to a valid slug');
        }
        
        return $cleaned;
    }

    /**
     * Boot method to automatically generate slug
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (empty($user->name_slug)) {
                $user->name_slug = self::generateSlug($user->name);
            }
        });

        static::updating(function ($user) {
            if ($user->isDirty('name')) {
                $user->name_slug = self::generateSlug($user->name);
            }
        });
    }
}
