<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;
use Illuminate\Auth\Access\Response;

/**
 * EventPolicyCMS
 *
 * Handles all authorization logic for Event actions.
 * Replaces manual role/ownership checks in EventControllerCMS.
 *
 * Roles:
 *  - admin     → full access to everything
 *  - organizer → can create events; can only edit/delete their own
 *  - attendee  → read-only; cannot create or manage events
 */
class EventPolicyCMS
{
    /**
     * Admins bypass all policy checks automatically.
     * This method runs before any other policy method.
     */
    public function before(User $user, string $ability): bool|null
    {
        if ($user->role === 'admin') {
            return true;
        }

        return null; // Let specific policy methods decide for non-admins
    }

    /**
     * Any authenticated user can view the events listing.
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Any user (including guests) can view a single published event.
     * Draft events are only visible to the owner or admin.
     */
    public function view(?User $user, Event $event): bool
    {
        if ($event->status === 'published') {
            return true;
        }

        // Draft events: only the owning organizer can see them
        return $user && $user->id === $event->user_id;
    }

    /**
     * Only admins and organizers can create events.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'organizer']);
    }

    /**
     * Only the event owner or admin can edit an event.
     */
    public function update(User $user, Event $event): Response
    {
        return $user->id === $event->user_id
            ? Response::allow()
            : Response::deny('You do not own this event.');
    }

    /**
     * Only the event owner or admin can delete an event.
     */
    public function delete(User $user, Event $event): Response
    {
        return $user->id === $event->user_id
            ? Response::allow()
            : Response::deny('You do not own this event.');
    }

    /**
     * Only the event owner or admin can manage (approve/decline) registrations.
     */
    public function manageRegistrations(User $user, Event $event): bool
    {
        return $user->id === $event->user_id;
    }
}