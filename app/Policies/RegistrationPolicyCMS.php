<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Auth\Access\Response;

/**
 * RegistrationPolicyCMS
 *
 * Handles all authorization logic for Registration actions.
 * Replaces manual ownership/role checks in RegistrationControllerCMS.
 *
 * Rules:
 *  - Only authenticated users can register for events
 *  - Users cannot register for their own events
 *  - Only the event owner or admin can approve/decline registrations
 *  - Users can only cancel their own registrations
 */
class RegistrationPolicyCMS
{
    /**
     * Admins bypass all policy checks automatically.
     */
    public function before(User $user, string $ability): bool|null
    {
        if ($user->role === 'admin') {
            return true;
        }

        return null;
    }

    /**
     * Organizers and admins can view all registrations for their events.
     * Attendees can only view their own registrations.
     */
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users; scope is applied in the controller
    }

    /**
     * A user can register for an event if:
     *  - The event is published
     *  - They are not the event organizer
     *  - They haven't already registered (enforced in controller/DB unique constraint)
     */
    public function register(User $user, Event $event): Response
    {
        if ($event->status !== 'published') {
            return Response::deny('This event is not open for registration.');
        }

        if ($user->id === $event->user_id) {
            return Response::deny('You cannot register for your own event.');
        }

        return Response::allow();
    }

    /**
     * A user can cancel their own registration.
     * Admins can cancel any registration (handled by before()).
     */
    public function cancel(User $user, Registration $registration): Response
    {
        return $user->id === $registration->user_id
            ? Response::allow()
            : Response::deny('You can only cancel your own registration.');
    }

    /**
     * Only the event owner (organizer) or admin can approve a registration.
     */
    public function approve(User $user, Registration $registration): Response
    {
        // Ensure event relationship is loaded
        $registration->loadMissing('event');

        return $user->id === $registration->event->user_id
            ? Response::allow()
            : Response::deny('Only the event organizer can approve registrations.');
    }

    /**
     * Only the event owner (organizer) or admin can decline a registration.
     */
    public function decline(User $user, Registration $registration): Response
    {
        $registration->loadMissing('event');

        return $user->id === $registration->event->user_id
            ? Response::allow()
            : Response::deny('Only the event organizer can decline registrations.');
    }
}