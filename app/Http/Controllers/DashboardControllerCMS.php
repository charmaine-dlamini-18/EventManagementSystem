<?php

namespace App\Http\Controllers;

use App\Models\EventCMS;
use App\Models\RegistrationCMS;
use App\Models\UserCMS;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * DashboardControllerCMS.
 *
 * Handles the dashboard view for all roles.
 * Admin sees site-wide stats.
 * Organizers see stats about their own events.
 * Attendees see stats about their own registrations.
 */
class DashboardControllerCMS extends ControllerCMS
{
    public function index(): View
    {
        $user = Auth::user();
        $stats = [];

        if ($user->role === 'admin') {
            $stats = [
                'total_events' => EventCMS::count(),
                'total_registrations' => RegistrationCMS::count(),
                'confirmed' => RegistrationCMS::confirmed()->count(),
                'pending' => RegistrationCMS::pending()->count(),
                'total_users' => UserCMS::count(),
                'upcoming_events' => EventCMS::upcoming()->published()->count(),
            ];
        } elseif ($user->role === 'organizer') {
            $myEventIds = EventCMS::byOrganizer($user->id)->pluck('id');

            $stats = [
                'my_events' => $myEventIds->count(),
                'total_registrations' => RegistrationCMS::forEvent(0)->whereIn('event_id', $myEventIds)->count(),
                'confirmed' => RegistrationCMS::confirmed()->whereIn('event_id', $myEventIds)->count(),
                'pending' => RegistrationCMS::pending()->whereIn('event_id', $myEventIds)->count(),
                'upcoming_events' => EventCMS::byOrganizer($user->id)->upcoming()->published()->count(),
            ];
        } else {
            $stats = [
                'events_attending' => RegistrationCMS::forUser($user->id)->confirmed()->count(),
                'confirmed' => RegistrationCMS::forUser($user->id)->confirmed()->count(),
                'pending' => RegistrationCMS::forUser($user->id)->pending()->count(),
                'upcoming' => RegistrationCMS::forUser($user->id)
                    ->confirmed()
                    ->whereHas('event', fn ($q) => $q->where('start_date', '>=', now()))
                    ->count(),
            ];
        }

        return view('dashboard', compact('stats'));
    }
}
