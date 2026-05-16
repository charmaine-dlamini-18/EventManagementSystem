<?php

namespace App\Http\Controllers;

use App\Mail\EventUpdatedCMS;
use App\Models\EventCMS;
use App\Models\RegistrationCMS;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

/**
 * EventControllerCMS
 *
 * Handles all Event CRUD operations.
 * Authorization is delegated to EventPolicyCMS via $this->authorize().
 * Notifies confirmed attendees when an event is updated.
 */
class EventControllerCMS extends ControllerCMS
{
    /**
     * Display a paginated, filterable list of events.
     */
    public function index(Request $request): View
    {
        $query = EventCMS::with('user');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $events = $query->latest()->paginate(12)->withQueryString();

        return view('events.index', compact('events'));
    }

    /**
     * Show the form for creating a new event.
     */
    public function create(): View
    {
        $this->authorize('create', EventCMS::class);

        return view('events.create');
    }

    /**
     * Store a newly created event.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', EventCMS::class);

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'location'    => 'required|string|max:255',
            'start_date'  => 'required|date|after:now',
            'end_date'    => 'required|date|after:start_date',
            'capacity'    => 'nullable|integer|min:1',
            'status'      => 'required|in:draft,published',
        ]);

        EventCMS::create(array_merge($validated, ['user_id' => Auth::id()]));

        return redirect()->route('events.index')
            ->with('success', 'Event created successfully.');
    }

    /**
     * Display a single event and its registrations.
     */
    public function show(EventCMS $event): View
    {
        $this->authorize('view', $event);

        $event->load('user');

        $registrations = RegistrationCMS::where('event_id', $event->id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('events.show', compact('event', 'registrations'));
    }

    /**
     * Show the edit form.
     */
    public function edit(EventCMS $event): View
    {
        $this->authorize('update', $event);

        return view('events.edit', compact('event'));
    }

    /**
     * Update the event and notify all confirmed attendees.
     *
     * Emails are queued — they do not block the response.
     * Only sends notifications if meaningful fields changed.
     */
    public function update(Request $request, EventCMS $event): RedirectResponse
    {
        $this->authorize('update', $event);

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'location'    => 'required|string|max:255',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after:start_date',
            'capacity'    => 'nullable|integer|min:1',
            'status'      => 'required|in:draft,published,cancelled',
        ]);

        // Track which fields changed to decide if attendees should be notified
        $notifyFields = ['title', 'start_date', 'end_date', 'location', 'status'];
        $hasImportantChange = collect($notifyFields)
            ->contains(fn($field) => $event->$field != ($validated[$field] ?? $event->$field));

        $event->update($validated);

        // Notify confirmed attendees only if key event details changed
        if ($hasImportantChange) {
            $confirmedRegistrations = $event->registrations()
                ->where('status', 'confirmed')
                ->with('user')
                ->get();

            foreach ($confirmedRegistrations as $registration) {
                Mail::to($registration->user->email)
                    ->queue(new EventUpdatedCMS($event, $registration->user));
            }
        }

        return redirect()->route('events.show', $event)
            ->with('success', 'Event updated successfully.' . ($hasImportantChange ? ' Attendees have been notified.' : ''));
    }

    /**
     * Delete the event.
     */
    public function destroy(EventCMS $event): RedirectResponse
    {
        $this->authorize('delete', $event);

        $event->delete();

        return redirect()->route('events.index')
            ->with('success', 'Event deleted successfully.');
    }

    /**
     * Display the calendar view of upcoming published events.
     */
    public function calendar(): View
    {
        $events = EventCMS::where('status', 'published')
            ->where('start_date', '>=', now())
            ->orderBy('start_date')
            ->get();

        return view('events.calendar', compact('events'));
    }
}