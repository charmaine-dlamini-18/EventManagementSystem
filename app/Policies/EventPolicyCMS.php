<?php

namespace App\Policies;

use App\Models\EventCMS;
use App\Models\UserCMS;
use Illuminate\Auth\Access\Response;

/**
 * EventPolicyCMS
 *
 * Handles all authorization logic for Event actions.
 *
 * Roles:
 *  - organizer → can create events; can only edit/delete their own
 *  - admin     → read-only; cannot create or manage events
 *  - attendee  → read-only; cannot create or manage events
 */
class EventPolicyCMS
{
    /**
     * Any user can view the events listing.
     */
    public function viewAny(?UserCMS $user): bool
    {
        return true;
    }

    /**
     * Any user (including guests) can view a single published event.
     * Draft events are only visible to the owning organizer.
     */
    public function view(?UserCMS $user, EventCMS $event): bool
    {
        if ($event->status === 'published') {
            return true;
        }

        return $user && $user->id === $event->user_id;
    }

    /**
     * Only organizers can create events.
     */
    public function create(UserCMS $user): bool
    {
        return $user->role === 'organizer';
    }

    /**
     * Only the event owner can edit an event.
     */
    public function update(UserCMS $user, EventCMS $event): Response
    {
        return $user->id === $event->user_id
            ? Response::allow()
            : Response::deny('You do not own this event.');
    }

    /**
     * Only the event owner can delete an event.
     */
    public function delete(UserCMS $user, EventCMS $event): Response
    {
        return $user->id === $event->user_id
            ? Response::allow()
            : Response::deny('You do not own this event.');
    }

    /**
     * Only the event owner can manage (approve/decline) registrations.
     */
    public function manageRegistrations(UserCMS $user, EventCMS $event): bool
    {
        return $user->id === $event->user_id;
    }
}