@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        
        <div class="bg-green-600 rounded p-6 text-white">
            <h3 class="text-xl font-bold">Welcome, {{ Auth::user()->name }}</h3>
            <p class="text-green-100">Your Role: {{ ucfirst(Auth::user()->role) }}</p>
        </div>

        <?php 
        $user = Auth::user();
        $userId = $user->id;
        
        if ($user->role == 'organizer' || $user->role == 'admin'):
            $myEventIds = \App\Models\Event::where('user_id', $userId)->pluck('id')->toArray();
            $totalRegistrations = \App\Models\Registration::whereIn('event_id', $myEventIds)->count();
            $confirmedCount = \App\Models\Registration::whereIn('event_id', $myEventIds)->where('status', 'confirmed')->count();
            $pendingCount = \App\Models\Registration::whereIn('event_id', $myEventIds)->where('status', 'pending')->count();
        else:
            $myRegistrations = \App\Models\Registration::where('user_id', $userId)->get();
            $eventsAttending = $myRegistrations->where('status', 'confirmed')->count();
            $confirmedCount = $myRegistrations->where('status', 'confirmed')->count();
            $pendingCount = $myRegistrations->where('status', 'pending')->count();
        endif;
        ?>
        
        <?php if ($user->role == 'organizer' || $user->role == 'admin'): ?>
        
        <div class="grid grid-cols-4 gap-4">
            <div class="bg-white rounded p-5 border">
                <p class="text-2xl font-bold">{{ $user->events()->count() }}</p>
                <p class="text-gray-500">My Events</p>
            </div>
            <div class="bg-white rounded p-5 border">
                <p class="text-2xl font-bold">{{ $totalRegistrations }}</p>
                <p class="text-gray-500">Total Registrations</p>
            </div>
            <div class="bg-white rounded p-5 border">
                <p class="text-2xl font-bold">{{ $confirmedCount }}</p>
                <p class="text-gray-500">Confirmed</p>
            </div>
            <div class="bg-white rounded p-5 border">
                <p class="text-2xl font-bold">{{ $pendingCount }}</p>
                <p class="text-gray-500">Pending</p>
            </div>
        </div>

        <div class="bg-white rounded border p-5">
            <h3 class="text-base font-semibold mb-4">Organizer Actions</h3>
            <a href="{{ route('events.index') }}" class="inline-block px-4 py-2 bg-green-600 text-white rounded mr-2">Browse Events</a>
            <a href="{{ route('events.create') }}" class="inline-block px-4 py-2 bg-green-600 text-white rounded mr-2">Create Event</a>
            <a href="{{ route('registrations.index') }}" class="inline-block px-4 py-2 bg-gray-200 text-gray-700 rounded mr-2">View Registrations</a>
            <a href="{{ route('events.calendar') }}" class="inline-block px-4 py-2 bg-gray-200 text-gray-700 rounded">Calendar</a>
        </div>

        <?php else: ?>
        
        <div class="grid grid-cols-3 gap-4">
            <div class="bg-white rounded p-5 border">
                <p class="text-2xl font-bold">{{ $eventsAttending }}</p>
                <p class="text-gray-500">Events Attending</p>
            </div>
            <div class="bg-white rounded p-5 border">
                <p class="text-2xl font-bold">{{ $confirmedCount }}</p>
                <p class="text-gray-500">Confirmed</p>
            </div>
            <div class="bg-white rounded p-5 border">
                <p class="text-2xl font-bold">{{ $pendingCount }}</p>
                <p class="text-gray-500">Pending</p>
            </div>
        </div>

        <div class="bg-white rounded border p-5">
            <h3 class="text-base font-semibold mb-4">Attendee Actions</h3>
            <a href="{{ route('events.index') }}" class="inline-block px-4 py-2 bg-green-600 text-white rounded mr-2">Browse Events</a>
            <a href="{{ route('registrations.index') }}" class="inline-block px-4 py-2 bg-gray-200 text-gray-700 rounded mr-2">My Registrations</a>
            <a href="{{ route('events.calendar') }}" class="inline-block px-4 py-2 bg-gray-200 text-gray-700 rounded">Calendar</a>
        </div>

        <?php endif; ?>

    </div>
</div>
@endsection