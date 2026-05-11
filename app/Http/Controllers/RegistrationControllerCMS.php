<?php

namespace App\Http\Controllers;

use App\Mail\NewRegistrationReceived;
use App\Mail\RegistrationApproved;
use App\Mail\RegistrationDeclined;
use App\Models\Event;
use App\Models\Registration;
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
class RegistrationControllerCMS extends Controller
{
    /**
     * Show registrations list.
     * - Attendees see their own registrations.
     * - Organizers/Admins see registrations for events they own.
     */
    public function index(): View
    {
        $user = Auth::user();

        if (in_array($user->role, ['admin', 'organizer'])) {
            // Organizers see registrations for their events
            $registrations = Registration::with(['event', 'user'])
                ->whereHas('event', fn($q) => $q->where('user_id', $user->id))
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        } else {
            // Attendees see only their own registrations
            $registrations = Registration::with('event')
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
    public function store(Event $event): RedirectResponse
    {
        $this->authorize('register', [Registration::class, $event]);

        $user = Auth::user();

        // Friendly duplicate check (DB unique constraint is the safety net)
        $alreadyRegistered = Registration::where('user_id', $user->id)
            ->where('event_id', $event->id)
            ->exists();

        if ($alreadyRegistered) {
            return back()->with('error', 'You are already registered for this event.');
        }

        // Check capacity
        if ($event->capacity) {
            $confirmedCount = Registration::where('event_id', $event->id)
                ->where('status', 'confirmed')
                ->count();

            if ($confirmedCount >= $event->capacity) {
                return back()->with('error', 'This event has reached full capacity.');
            }
        }

        $registration = Registration::create([
            'user_id'  => $user->id,
            'event_id' => $event->id,
            'status'   => 'pending',
        ]);

        // Load relationships needed for the email
        $registration->load(['event.user', 'user']);

        // Notify the organizer a new attendee has registered
        Mail::to($registration->event->user->email)
            ->queue(new NewRegistrationReceived($registration));

        return redirect()->route('events.show', $event)
            ->with('success', 'Registered! Awaiting organizer approval.');
    }

    /**
     * Cancel (unregister) the authenticated user's registration.
     */
    public function unregister(Event $event): RedirectResponse
    {
        $registration = Registration::where('user_id', Auth::id())
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
    public function approve(Registration $registration): RedirectResponse
    {
        $this->authorize('approve', $registration);

        $registration->loadMissing(['event', 'user']);

        // Enforce capacity before approving
        if ($registration->event->capacity) {
            $confirmedCount = Registration::where('event_id', $registration->event_id)
                ->where('status', 'confirmed')
                ->count();

            if ($confirmedCount >= $registration->event->capacity) {
                return back()->with('error', 'Event is at full capacity. Cannot approve.');
            }
        }

        $registration->update(['status' => 'confirmed']);

        // Notify the attendee their registration was approved
        Mail::to($registration->user->email)
            ->queue(new RegistrationApproved($registration));

        return back()->with('success', 'Registration approved. Attendee notified by email.');
    }

    /**
     * Decline a registration.
     * Sends a decline notification email to the attendee.
     */
    public function decline(Registration $registration): RedirectResponse
    {
        $this->authorize('decline', $registration);

        $registration->loadMissing(['event', 'user']);

        $registration->update(['status' => 'cancelled']);

        // Notify the attendee their registration was declined
        Mail::to($registration->user->email)
            ->queue(new RegistrationDeclined($registration));

        return back()->with('success', 'Registration declined. Attendee notified by email.');
    }
}