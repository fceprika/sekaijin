<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\User;

class ArticlePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
        // Anyone can view published articles
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Article $article): bool
    {
        // Anyone can view published articles
        if ($article->is_published) {
            return true;
        }
        
        // Authors can view their own unpublished articles
        return $user && $article->author_id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Any authenticated user can create articles
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Article $article): bool
    {
        // Admins can update any article
        if ($user->isAdmin()) {
            return true;
        }
        
        // Authors can only update their own articles
        return $article->author_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Article $article): bool
    {
        // Admins can delete any article
        if ($user->isAdmin()) {
            return true;
        }
        
        // Authors can only delete their own articles
        return $article->author_id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Article $article): bool
    {
        // Only admins can restore articles
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Article $article): bool
    {
        // Only admins can permanently delete articles
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can feature the model.
     */
    public function feature(User $user, Article $article): bool
    {
        // Only admins and ambassadors can feature articles
        return $user->isAdmin() || $user->isAmbassador();
    }

    /**
     * Determine whether the user can publish/unpublish the model.
     */
    public function publish(User $user, Article $article): bool
    {
        // Admins can publish/unpublish any article
        if ($user->isAdmin()) {
            return true;
        }
        
        // Authors can publish/unpublish their own articles
        return $article->author_id === $user->id;
    }
}