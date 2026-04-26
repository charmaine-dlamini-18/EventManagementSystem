<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RegistrationControllerCMS extends Controller
{
    public function index(): View
    {
        $userId = Auth::id();
        $registrations = Registration::with('event')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('registrations.index', compact('registrations'));
    }

    public function store(Request $request): RedirectResponse
    {
        $event = Event::findOrFail($request->route('event'));
        
        $user = Auth::user();
        
        if ($event->user_id == $user->id) {
            return back()->with('error', 'You cannot register for your own event.');
        }
        
        if ($event->status != 'published') {
            return back()->with('error', 'Cannot register for this event.');
        }

        $existing = Registration::where('user_id', $user->id)
            ->where('event_id', $event->id)
            ->first();

        if ($existing) {
            return back()->with('error', 'Already registered.');
        }

        $registration = new Registration();
        $registration->user_id = $user->id;
        $registration->event_id = $event->id;
        $registration->status = 'pending';
        $registration->save();

        return redirect()->route('events.show', $event)->with('success', 'Registered! Waiting for organizer approval.');
    }

    public function unregister(Request $request, Event $event): RedirectResponse
    {
        $user = Auth::user();
        
        $registration = Registration::where('user_id', $user->id)
            ->where('event_id', $event->id)
            ->first();
        
        if (!$registration) {
            return redirect()->back()->with('error', 'Registration not found.');
        }
        
        $registration->delete();
        
        return redirect()->back()->with('success', 'Registration cancelled successfully.');
    }

    public function approve(Registration $registration): RedirectResponse
    {
        $user = Auth::user();
        $registration->load('event');
        
        if ($user->id != $registration->event->user_id && $user->role != 'admin') {
            return back()->with('error', 'Unauthorized.');
        }

        $confirmed = Registration::where('event_id', $registration->event->id)
            ->where('status', 'confirmed')
            ->count();
            
        if ($registration->event->capacity && $confirmed >= $registration->event->capacity) {
            return back()->with('error', 'Event is at full capacity.');
        }

        $registration->status = 'confirmed';
        $registration->save();
        
        return back()->with('success', 'Registration approved.');
    }

    public function decline(Registration $registration): RedirectResponse
    {
        $user = Auth::user();
        $registration->load('event');
        
        if ($user->id != $registration->event->user_id && $user->role != 'admin') {
            return back()->with('error', 'Unauthorized.');
        }

        $registration->status = 'cancelled';
        $registration->save();
        
        return back()->with('success', 'Registration declined.');
    }
}