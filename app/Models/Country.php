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
     * Get users living in this country
     */
    public function users()
    {
        return $this->hasMany(User::class, 'country_residence', 'name_fr');
    }
}
