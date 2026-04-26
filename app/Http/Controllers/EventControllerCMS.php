<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class EventControllerCMS extends Controller
{
    public function index(Request $request): View
    {
        $query = Event::with('user');

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%'.$search.'%')
                  ->orWhere('description', 'like', '%'.$search.'%');
            });
        }

        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        $events = $query->latest()->paginate(12);
            
        return view('events.index', compact('events'));
    }

    public function create(): View
    {
        return view('events.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'capacity' => 'nullable|integer|min:1',
            'status' => 'required|in:draft,published',
        ]);

        $event = new Event();
        $event->user_id = $request->user()->id;
        $event->title = $validated['title'];
        $event->description = $validated['description'];
        $event->location = $validated['location'];
        $event->start_date = $validated['start_date'];
        $event->end_date = $validated['end_date'];
        $event->capacity = $validated['capacity'];
        $event->status = $validated['status'];
        $event->save();

        return redirect()->route('events.index')->with('success', 'Event created successfully.');
    }

    public function show(Event $event): View
    {
        $event->load('user');
        $registrations = RegistrationCMS::where('event_id', $event->id)->with('user')->get();
        return view('events.show', compact('event', 'registrations'));
    }

    public function edit(Event $event): View
    {
        $user = Auth::user();
        if ($user->id != $event->user_id && $user->role != 'admin') {
            return redirect()->route('events.index')->with('error', 'Unauthorized.');
        }
        return view('events.edit', compact('event'));
    }

    public function update(Request $request, Event $event): RedirectResponse
    {
        $user = Auth::user();
        if ($user->id != $event->user_id && $user->role != 'admin') {
            return redirect()->route('events.index')->with('error', 'Unauthorized.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'capacity' => 'nullable|integer|min:1',
            'status' => 'required|in:draft,published',
        ]);

        $event->title = $validated['title'];
        $event->description = $validated['description'];
        $event->location = $validated['location'];
        $event->start_date = $validated['start_date'];
        $event->end_date = $validated['end_date'];
        $event->capacity = $validated['capacity'];
        $event->status = $validated['status'];
        $event->save();

        return redirect()->route('events.show', $event)->with('success', 'Event updated successfully.');
    }

    public function calendar(): View
    {
        $events = Event::where('status', 'published')
            ->where('start_date', '>=', date('Y-m-d H:i:s'))
            ->orderBy('start_date')
            ->get();
            
        return view('events.calendar', compact('events'));
    }

    public function destroy(Event $event): RedirectResponse
    {
        $user = Auth::user();
        if ($user->id != $event->user_id && $user->role != 'admin') {
            return redirect()->route('events.index')->with('error', 'Unauthorized.');
        }
        
        $event->delete();
        
        return redirect()->route('events.index')->with('success', 'Event deleted successfully.');
    }
}