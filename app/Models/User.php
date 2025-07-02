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
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'role',
        'birth_date',
        'phone',
        'country_residence',
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
}
