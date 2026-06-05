@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3 text-sm">{{ session('success') }}</div>
        @endif

        <!-- Welcome Banner -->
        <div class="bg-green-600 rounded-xl p-6 text-white">
            <h1 class="text-xl font-bold">Welcome back, {{ Auth::user()->name }}!</h1>
            <p class="text-green-100 mt-0.5">{{ Auth::user()->role_label }}</p>
        </div>

        @php $role = Auth::user()->role; @endphp

        @if($role === 'admin')
            {{-- Admin Dashboard --}}

            <div class="grid grid-cols-2 md:grid-cols-2 gap-4">
                <div class="bg-white rounded-xl border p-5 shadow-sm">
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_events'] }}</p>
                    <p class="text-sm text-gray-500 mt-0.5">Total Events</p>
                </div>
                <div class="bg-white rounded-xl border p-5 shadow-sm">
                    <p class="text-2xl font-bold text-purple-600">{{ $stats['total_users'] }}</p>
                    <p class="text-sm text-gray-500 mt-0.5">Total Users</p>
                </div>
            </div>

            <div class="bg-white rounded-xl border shadow-sm p-5">
                <h2 class="text-base font-semibold text-gray-900 mb-4">Quick Actions</h2>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('users.index') }}" class="px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition">
                        Manage Users
                    </a>
                    <a href="{{ route('events.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition">
                        Browse Events
                    </a>
                    <a href="{{ route('events.calendar') }}" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition">
                        Calendar
                    </a>
                </div>
            </div>

        @elseif($role === 'organizer')
            {{-- Organizer Dashboard --}}

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white rounded-xl border p-5 shadow-sm">
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['my_events'] }}</p>
                    <p class="text-sm text-gray-500 mt-0.5">My Events</p>
                </div>
                <div class="bg-white rounded-xl border p-5 shadow-sm">
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['upcoming_events'] }}</p>
                    <p class="text-sm text-gray-500 mt-0.5">Upcoming Events</p>
                </div>
                <div class="bg-white rounded-xl border p-5 shadow-sm">
                    <p class="text-2xl font-bold text-green-600">{{ $stats['confirmed'] }}</p>
                    <p class="text-sm text-gray-500 mt-0.5">Confirmed</p>
                </div>
                <div class="bg-white rounded-xl border p-5 shadow-sm">
                    <p class="text-2xl font-bold text-amber-500">{{ $stats['pending'] }}</p>
                    <p class="text-sm text-gray-500 mt-0.5">Pending</p>
                </div>
            </div>

            @if($stats['pending'] > 0)
                <div class="bg-amber-50 border border-amber-200 text-amber-800 rounded-xl px-5 py-4 text-sm font-medium">
                    ⏳ You have <strong>{{ $stats['pending'] }}</strong> pending registration(s) awaiting your approval.
                    <a href="{{ route('registrations.index') }}" class="underline ml-1">Review now →</a>
                </div>
            @endif

            <div class="bg-white rounded-xl border shadow-sm p-5">
                <h2 class="text-base font-semibold text-gray-900 mb-4">Quick Actions</h2>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('events.create') }}" class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition">
                        + Create Event
                    </a>
                    <a href="{{ route('events.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition">
                        Browse Events
                    </a>
                    <a href="{{ route('registrations.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition">
                        View Registrations
                    </a>
                    <a href="{{ route('events.calendar') }}" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition">
                        Calendar
                    </a>
                </div>
            </div>

        @else
            {{-- Attendee Dashboard --}}

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white rounded-xl border p-5 shadow-sm">
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['events_attending'] }}</p>
                    <p class="text-sm text-gray-500 mt-0.5">Events Attending</p>
                </div>
                <div class="bg-white rounded-xl border p-5 shadow-sm">
                    <p class="text-2xl font-bold text-green-600">{{ $stats['confirmed'] }}</p>
                    <p class="text-sm text-gray-500 mt-0.5">Confirmed</p>
                </div>
                <div class="bg-white rounded-xl border p-5 shadow-sm">
                    <p class="text-2xl font-bold text-amber-500">{{ $stats['pending'] }}</p>
                    <p class="text-sm text-gray-500 mt-0.5">Pending Approval</p>
                </div>
                <div class="bg-white rounded-xl border p-5 shadow-sm">
                    <p class="text-2xl font-bold text-blue-600">{{ $stats['upcoming'] }}</p>
                    <p class="text-sm text-gray-500 mt-0.5">Upcoming Events</p>
                </div>
            </div>

            <div class="bg-white rounded-xl border shadow-sm p-5">
                <h2 class="text-base font-semibold text-gray-900 mb-4">Quick Actions</h2>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('events.index') }}" class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition">
                        Browse Events
                    </a>
                    <a href="{{ route('registrations.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition">
                        My Registrations
                    </a>
                    <a href="{{ route('events.calendar') }}" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition">
                        Calendar
                    </a>
                </div>
            </div>
        @endif

    </div>
</div>
@endsection