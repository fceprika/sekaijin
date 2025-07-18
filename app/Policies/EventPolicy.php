<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;

class EventPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
        // Anyone can view published events
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Event $event): bool
    {
        // Anyone can view published events
        if ($event->is_published) {
            return true;
        }

        // Organizers can view their own unpublished events
        return $user && $event->organizer_id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only ambassadors and admins can create events
        return $user->isAdmin() || $user->isAmbassador();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Event $event): bool
    {
        // Admins can update any event
        if ($user->isAdmin()) {
            return true;
        }

        // Organizers can only update their own events
        return $event->organizer_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Event $event): bool
    {
        // Admins can delete any event
        if ($user->isAdmin()) {
            return true;
        }

        // Organizers can only delete their own events
        return $event->organizer_id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Event $event): bool
    {
        // Only admins can restore events
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Event $event): bool
    {
        // Only admins can permanently delete events
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can feature the model.
     */
    public function feature(User $user, Event $event): bool
    {
        // Only admins and ambassadors can feature events
        return $user->isAdmin() || $user->isAmbassador();
    }

    /**
     * Determine whether the user can publish/unpublish the model.
     */
    public function publish(User $user, Event $event): bool
    {
        // Admins can publish/unpublish any event
        if ($user->isAdmin()) {
            return true;
        }

        // Organizers can publish/unpublish their own events
        return $event->organizer_id === $user->id;
    }

    /**
     * Determine whether the user can manage participants.
     */
    public function manageParticipants(User $user, Event $event): bool
    {
        // Admins can manage participants for any event
        if ($user->isAdmin()) {
            return true;
        }

        // Organizers can manage participants for their own events
        return $event->organizer_id === $user->id;
    }
}
