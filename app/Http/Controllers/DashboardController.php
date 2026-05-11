<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * DashboardController
 *
 * Handles the dashboard view for all roles.
 * Organizers/Admins see stats about their events.
 * Attendees see stats about their own registrations.
 */
class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $stats = [];

        if (in_array($user->role, ['admin', 'organizer'])) {
            $myEventIds = Event::byOrganizer($user->id)->pluck('id');

            $stats = [
                'my_events'           => $myEventIds->count(),
                'total_registrations' => Registration::forEvent(0)->whereIn('event_id', $myEventIds)->count(),
                'confirmed'           => Registration::confirmed()->whereIn('event_id', $myEventIds)->count(),
                'pending'             => Registration::pending()->whereIn('event_id', $myEventIds)->count(),
                'upcoming_events'     => Event::byOrganizer($user->id)->upcoming()->published()->count(),
            ];
        } else {
            $stats = [
                'events_attending' => Registration::forUser($user->id)->confirmed()->count(),
                'confirmed'        => Registration::forUser($user->id)->confirmed()->count(),
                'pending'          => Registration::forUser($user->id)->pending()->count(),
                'upcoming'         => Registration::forUser($user->id)
                    ->confirmed()
                    ->whereHas('event', fn($q) => $q->where('start_date', '>=', now()))
                    ->count(),
            ];
        }

        return view('dashboard', compact('stats'));
    }
}