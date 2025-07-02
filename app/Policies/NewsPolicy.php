<?php

namespace App\Policies;

use App\Models\News;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class NewsPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
        // Anyone can view published news
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, News $news): bool
    {
        // Anyone can view published news
        if ($news->is_published) {
            return true;
        }
        
        // Only admins and ambassadors can view unpublished news
        return $user && ($user->isAdmin() || $user->isAmbassador());
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only admins and ambassadors can create news
        return $user->isAdmin() || $user->isAmbassador();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, News $news): bool
    {
        // Admins can update any news
        if ($user->isAdmin()) {
            return true;
        }
        
        // Ambassadors can only update their own news
        return $user->isAmbassador() && $news->author_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, News $news): bool
    {
        // Admins can delete any news
        if ($user->isAdmin()) {
            return true;
        }
        
        // Ambassadors can only delete their own news
        return $user->isAmbassador() && $news->author_id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, News $news): bool
    {
        // Only admins can restore news
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, News $news): bool
    {
        // Only admins can permanently delete news
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can feature the model.
     */
    public function feature(User $user, News $news): bool
    {
        // Only admins can feature news
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can publish/unpublish the model.
     */
    public function publish(User $user, News $news): bool
    {
        // Admins can publish/unpublish any news
        if ($user->isAdmin()) {
            return true;
        }
        
        // Ambassadors can publish/unpublish their own news
        return $user->isAmbassador() && $news->author_id === $user->id;
    }
}