<?php

namespace App\Policies;

use App\Models\EventCMS;
use App\Models\RegistrationCMS;
use App\Models\UserCMS;
use Illuminate\Auth\Access\Response;

/**
 * RegistrationPolicyCMS
 *
 * Handles all authorization logic for Registration actions.
 *
 * Rules:
 *  - Only authenticated users can register for events
 *  - Users cannot register for their own events
 *  - Only the event owner can approve/decline registrations
 *  - Users can only cancel their own registrations
 */
class RegistrationPolicyCMS
{
    /**
     * All authenticated users; scope is applied in the controller.
     */
    public function viewAny(UserCMS $user): bool
    {
        return true;
    }

    /**
     * A user can register for an event if:
     *  - The event is published
     *  - They are not the event organizer
     */
    public function register(UserCMS $user, EventCMS $event): Response
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
     */
    public function cancel(UserCMS $user, RegistrationCMS $registration): Response
    {
        return $user->id === $registration->user_id
            ? Response::allow()
            : Response::deny('You can only cancel your own registration.');
    }

    /**
     * Only the event owner can approve a registration.
     */
    public function approve(UserCMS $user, RegistrationCMS $registration): Response
    {
        $registration->loadMissing('event');

        return $user->id === $registration->event->user_id
            ? Response::allow()
            : Response::deny('Only the event organizer can approve registrations.');
    }

    /**
     * Only the event owner can decline a registration.
     */
    public function decline(UserCMS $user, RegistrationCMS $registration): Response
    {
        $registration->loadMissing('event');

        return $user->id === $registration->event->user_id
            ? Response::allow()
            : Response::deny('Only the event organizer can decline registrations.');
    }
}