<?php

namespace App\Http\Controllers;

use App\Mail\NewRegistrationReceivedCMS;
use App\Mail\RegistrationApprovedCMS;
use App\Mail\RegistrationDeclinedCMS;
use App\Models\EventCMS;
use App\Models\RegistrationCMS;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

/**
 * RegistrationControllerCMS
 *
 * Handles event registration for attendees and approval/decline by organizers.
 * Authorization is delegated to RegistrationPolicyCMS via $this->authorize().
 * Email notifications are queued (non-blocking) via Laravel Mail + ShouldQueue.
 */
class RegistrationControllerCMS extends ControllerCMS
{
    /**
     * Show registrations list.
     * - Admin sees all registrations.
     * - Organizers see registrations for their own events.
     * - Attendees see only their own registrations.
     */
    public function index(): View
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $registrations = RegistrationCMS::with(['event', 'user'])
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        } elseif ($user->role === 'organizer') {
            $registrations = RegistrationCMS::with(['event', 'user'])
                ->whereHas('event', fn($q) => $q->where('user_id', $user->id))
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        } else {
            $registrations = RegistrationCMS::with('event')
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        }

        return view('registrations.index', compact('registrations'));
    }

    /**
     * Register the authenticated user for an event.
     * Notifies the organizer via email after successful registration.
     */
    public function store(EventCMS $event): RedirectResponse
    {
        $this->authorize('register', [RegistrationCMS::class, $event]);

        $user = Auth::user();

        // Friendly duplicate check (DB unique constraint is the safety net)
        $alreadyRegistered = RegistrationCMS::where('user_id', $user->id)
            ->where('event_id', $event->id)
            ->exists();

        if ($alreadyRegistered) {
            return back()->with('error', 'You are already registered for this event.');
        }

        // Check capacity
        if ($event->capacity) {
            $confirmedCount = RegistrationCMS::where('event_id', $event->id)
                ->where('status', 'confirmed')
                ->count();

            if ($confirmedCount >= $event->capacity) {
                return back()->with('error', 'This event has reached full capacity.');
            }
        }

        $registration = RegistrationCMS::create([
            'user_id'  => $user->id,
            'event_id' => $event->id,
            'status'   => 'pending',
        ]);

        // Load relationships needed for the email
        $registration->load(['event.user', 'user']);

        // Notify the organizer a new attendee has registered
        Mail::to($registration->event->user->email)
            ->queue(new NewRegistrationReceivedCMS($registration));

        return redirect()->route('events.show', $event)
            ->with('success', 'Registered! Awaiting organizer approval.');
    }

    /**
     * Cancel (unregister) the authenticated user's registration.
     */
    public function unregister(EventCMS $event): RedirectResponse
    {
        $registration = RegistrationCMS::where('user_id', Auth::id())
            ->where('event_id', $event->id)
            ->firstOrFail();

        $this->authorize('cancel', $registration);

        $registration->delete();

        return back()->with('success', 'Registration cancelled successfully.');
    }

    /**
     * Approve a registration.
     * Sends a confirmation email to the attendee.
     */
    public function approve(RegistrationCMS $registration): RedirectResponse
    {
        $this->authorize('approve', $registration);

        $registration->loadMissing(['event', 'user']);

        // Enforce capacity before approving
        if ($registration->event->capacity) {
            $confirmedCount = RegistrationCMS::where('event_id', $registration->event_id)
                ->where('status', 'confirmed')
                ->count();

            if ($confirmedCount >= $registration->event->capacity) {
                return back()->with('error', 'Event is at full capacity. Cannot approve.');
            }
        }

        $registration->update(['status' => 'confirmed']);

        // Notify the attendee their registration was approved
        Mail::to($registration->user->email)
            ->queue(new RegistrationApprovedCMS($registration));

        return back()->with('success', 'Registration approved. Attendee notified by email.');
    }

    /**
     * Decline a registration.
     * Sends a decline notification email to the attendee.
     */
    public function decline(RegistrationCMS $registration): RedirectResponse
    {
        $this->authorize('decline', $registration);

        $registration->loadMissing(['event', 'user']);

        $registration->update(['status' => 'cancelled']);

        // Notify the attendee their registration was declined
        Mail::to($registration->user->email)
            ->queue(new RegistrationDeclinedCMS($registration));

        return back()->with('success', 'Registration declined. Attendee notified by email.');
    }
}