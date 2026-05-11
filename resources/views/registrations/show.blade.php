@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-6">

        <a href="{{ route('registrations.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Back to Registrations
        </a>

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="h-2 bg-green-600"></div>
            <div class="p-6 space-y-5">

                <h1 class="text-xl font-bold text-gray-900">Registration Details</h1>

                <!-- Event Info -->
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase mb-1">Event</p>
                    <a href="{{ route('events.show', $registration->event) }}" class="text-base font-semibold text-gray-900 hover:text-green-700 transition">
                        {{ $registration->event->title }}
                    </a>
                    <p class="text-sm text-gray-500 mt-0.5">{{ $registration->event->start_date->format('l, F j, Y \a\t g:i A') }}</p>
                    <p class="text-sm text-gray-500">{{ $registration->event->location }}</p>
                </div>

                <!-- Attendee Info -->
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase mb-1">Attendee</p>
                    <p class="text-sm font-medium text-gray-900">{{ $registration->user->name }}</p>
                    <p class="text-sm text-gray-500">{{ $registration->user->email }}</p>
                </div>

                <!-- Notes -->
                @if($registration->notes)
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase mb-1">Notes</p>
                        <p class="text-sm text-gray-700">{{ $registration->notes }}</p>
                    </div>
                @endif

                <!-- Status Badge -->
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase mb-2">Status</p>
                    <span class="px-3 py-1 text-sm font-medium rounded-full
                        @if($registration->status === 'confirmed') bg-green-100 text-green-700
                        @elseif($registration->status === 'pending') bg-amber-100 text-amber-700
                        @else bg-red-100 text-red-700 @endif">
                        {{ $registration->status_badge }}
                    </span>
                </div>

                <!-- Organizer Actions: Approve / Decline -->
                @if($registration->is_pending)
                    @can('approve', $registration)
                        <div class="flex gap-3 pt-4 border-t border-gray-100">
                            <form method="POST" action="{{ route('registrations.approve', $registration) }}">
                                @csrf
                                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition">
                                    ✅ Approve
                                </button>
                            </form>
                            <form method="POST" action="{{ route('registrations.decline', $registration) }}">
                                @csrf
                                <button type="submit" class="px-4 py-2 text-sm font-medium text-red-700 bg-red-50 rounded-lg hover:bg-red-100 transition">
                                    ❌ Decline
                                </button>
                            </form>
                        </div>
                    @endcan
                @endif

                <!-- Attendee Cancel Action -->
                @can('cancel', $registration)
                    @if($registration->status !== 'cancelled')
                        <div class="pt-4 border-t border-gray-100">
                            <form method="POST" action="{{ route('events.unregister', $registration->event) }}"
                                  onsubmit="return confirm('Cancel your registration for this event?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-4 py-2 text-sm font-medium text-red-700 bg-red-50 rounded-lg hover:bg-red-100 transition">
                                    Cancel Registration
                                </button>
                            </form>
                        </div>
                    @endif
                @endcan

            </div>
        </div>

    </div>
</div>
@endsection