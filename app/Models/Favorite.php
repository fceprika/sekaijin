<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'favoritable_type',
        'favoritable_id',
    ];

    /**
     * Relation polymorphe vers l'élément favori (Article ou News).
     */
    public function favoritable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Relation vers l'utilisateur propriétaire du favori.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope pour récupérer les favoris d'un utilisateur.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope pour récupérer les favoris d'un type spécifique.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('favoritable_type', $type);
    }
}
