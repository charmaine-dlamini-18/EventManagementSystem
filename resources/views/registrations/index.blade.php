@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

        <div class="flex justify-between items-center">
            <div>
                @if(in_array(auth()->user()->role, ['admin', 'organizer']))
                    <h1 class="text-2xl font-bold text-gray-900">Event Registrations</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Registrations for your events — approve or decline attendees</p>
                @else
                    <h1 class="text-2xl font-bold text-gray-900">My Registrations</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Your event registrations</p>
                @endif
            </div>
            <a href="{{ route('events.index') }}" class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 transition text-sm font-medium">
                <i data-lucide="search" class="w-4 h-4"></i> Browse Events
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3 text-sm">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 text-sm">{{ session('error') }}</div>
        @endif

        @if($registrations->isEmpty())
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-12 text-center">
                <div class="w-14 h-14 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="clipboard-x" class="w-7 h-7 text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No registrations yet</h3>
                <p class="text-gray-500 text-sm mb-6">Browse events to get started.</p>
                <a href="{{ route('events.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm font-medium">Browse Events</a>
            </div>
        @else
            <div class="space-y-4">
                @foreach($registrations as $registration)
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <a href="{{ route('events.show', $registration->event) }}" class="text-base font-semibold text-gray-900 hover:text-green-700 transition">
                                        {{ $registration->event->title }}
                                    </a>
                                    <span class="px-2.5 py-1 text-xs font-medium rounded-full
                                        @if($registration->status === 'confirmed') bg-green-100 text-green-700
                                        @elseif($registration->status === 'pending') bg-amber-100 text-amber-700
                                        @else bg-red-100 text-red-700 @endif">
                                        {{ $registration->status_badge }}
                                    </span>
                                </div>

                                {{-- Organizers see the attendee name; attendees see location/date --}}
                                @if(in_array(auth()->user()->role, ['admin', 'organizer']))
                                    <div class="flex items-center gap-2 text-sm text-gray-500">
                                        <i data-lucide="user" class="w-4 h-4"></i>
                                        <span>{{ $registration->user->name }} — {{ $registration->user->email }}</span>
                                    </div>
                                @endif

                                <div class="flex flex-wrap gap-4 text-sm text-gray-500 mt-1">
                                    <div class="flex items-center gap-1.5">
                                        <i data-lucide="calendar" class="w-4 h-4"></i>
                                        {{ $registration->event->start_date->format('M d, Y') }}
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <i data-lucide="map-pin" class="w-4 h-4"></i>
                                        {{ Str::limit($registration->event->location, 30) }}
                                    </div>
                                </div>
                            </div>

                            <div class="flex gap-2 flex-wrap">
                                <a href="{{ route('events.show', $registration->event) }}"
                                   class="px-3 py-1.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                                    View Event
                                </a>

                                {{-- Organizer: approve / decline pending --}}
                                @if($registration->is_pending)
                                    @can('approve', $registration)
                                        <form method="POST" action="{{ route('registrations.approve', $registration) }}">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 text-sm font-medium text-green-700 bg-green-50 rounded-lg hover:bg-green-100 transition">
                                                Approve
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('registrations.decline', $registration) }}">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 text-sm font-medium text-red-700 bg-red-50 rounded-lg hover:bg-red-100 transition">
                                                Decline
                                            </button>
                                        </form>
                                    @endcan
                                @endif

                                {{-- Attendee: cancel their own registration --}}
                                @can('cancel', $registration)
                                    @if($registration->status !== 'cancelled')
                                        <form method="POST" action="{{ route('events.unregister', $registration->event) }}"
                                              onsubmit="return confirm('Cancel your registration?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1.5 text-sm font-medium text-red-700 bg-red-50 rounded-lg hover:bg-red-100 transition">
                                                Cancel
                                            </button>
                                        </form>
                                    @endif
                                @endcan
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="flex justify-center">{{ $registrations->links() }}</div>
        @endif

    </div>
</div>
@endsection