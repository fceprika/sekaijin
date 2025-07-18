<?php

namespace App\Policies;

use App\Models\Favorite;
use App\Models\User;

class FavoritePolicy
{
    /**
     * Determine whether the user can view any favorites.
     */
    public function viewAny(User $user): bool
    {
        // Users can only view their own favorites
        return true;
    }

    /**
     * Determine whether the user can view the favorite.
     */
    public function view(User $user, Favorite $favorite): bool
    {
        // Users can only view their own favorites
        return $user->id === $favorite->user_id;
    }

    /**
     * Determine whether the user can create favorites.
     */
    public function create(User $user): bool
    {
        // All authenticated users can create favorites
        return true;
    }

    /**
     * Determine whether the user can update the favorite.
     */
    public function update(User $user, Favorite $favorite): bool
    {
        // Favorites are toggle-only, no updates allowed
        return false;
    }

    /**
     * Determine whether the user can delete the favorite.
     */
    public function delete(User $user, Favorite $favorite): bool
    {
        // Users can only delete their own favorites
        return $user->id === $favorite->user_id;
    }

    /**
     * Determine whether the user can restore the favorite.
     */
    public function restore(User $user, Favorite $favorite): bool
    {
        // No soft deletes on favorites
        return false;
    }

    /**
     * Determine whether the user can permanently delete the favorite.
     */
    public function forceDelete(User $user, Favorite $favorite): bool
    {
        // Only admins can force delete favorites
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can toggle favorite on content.
     */
    public function toggle(User $user): bool
    {
        // All authenticated users can toggle favorites
        return true;
    }
}
