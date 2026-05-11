<?php

namespace App\Observers;

use App\Models\Registration;
use Illuminate\Support\Facades\Log;

/**
 * RegistrationObserver
 *
 * Listens to the Eloquent lifecycle of the Registration model.
 * Registered in AppServiceProvider via Registration::observe(RegistrationObserver::class).
 */
class RegistrationObserver
{
    /**
     * Fires AFTER a new registration is created.
     * Logs the new registration for audit trail.
     */
    public function created(Registration $registration): void
    {
        Log::info('New registration created', [
            'registration_id' => $registration->id,
            'user_id'         => $registration->user_id,
            'event_id'        => $registration->event_id,
            'status'          => $registration->status,
        ]);
    }

    /**
     * Fires AFTER a registration status is updated.
     * Logs status transitions (pending → confirmed, pending → cancelled, etc.)
     */
    public function updated(Registration $registration): void
    {
        if ($registration->wasChanged('status')) {
            Log::info('Registration status changed', [
                'registration_id' => $registration->id,
                'user_id'         => $registration->user_id,
                'event_id'        => $registration->event_id,
                'from'            => $registration->getOriginal('status'),
                'to'              => $registration->status,
            ]);
        }
    }

    /**
     * Fires BEFORE a registration is deleted (attendee cancels).
     * Logs the cancellation for the audit trail.
     */
    public function deleting(Registration $registration): void
    {
        Log::info('Registration deleted (attendee cancelled)', [
            'registration_id' => $registration->id,
            'user_id'         => $registration->user_id,
            'event_id'        => $registration->event_id,
        ]);
    }
}