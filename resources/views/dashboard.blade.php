@extends('layouts.app')

@section('content')
<div style="padding:2rem 0;">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" style="padding-left:1rem;padding-right:1rem;">

        @if(session('success'))
            <div class="alert alert-success" style="margin-bottom:1.5rem;">{{ session('success') }}</div>
        @endif

        {{-- Welcome Banner --}}
        <div style="background:linear-gradient(135deg,rgba(22,163,74,0.18) 0%,rgba(22,163,74,0.05) 100%);border:1px solid rgba(22,163,74,0.25);border-radius:16px;padding:1.75rem 2rem;margin-bottom:2rem;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
            <div>
                <p style="font-size:0.75rem;color:#4ade80;text-transform:uppercase;letter-spacing:0.1em;font-weight:600;margin-bottom:0.3rem;">Welcome back</p>
                <h1 style="font-family:'Syne',sans-serif;font-weight:800;font-size:1.75rem;color:#e6edf3;letter-spacing:-0.03em;margin:0;">{{ Auth::user()->name }}</h1>
                <p style="font-size:0.875rem;color:rgba(230,237,243,0.45);margin:0.25rem 0 0;">{{ Auth::user()->role_label }}</p>
            </div>
            <div style="width:52px;height:52px;background:rgba(22,163,74,0.2);border-radius:14px;display:flex;align-items:center;justify-content:center;">
                <i data-lucide="calendar-check" style="width:26px;height:26px;color:#4ade80;"></i>
            </div>
        </div>

        @php $role = Auth::user()->role; @endphp

        @if($role === 'admin')
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:1rem;margin-bottom:2rem;">
                <div class="stat-card">
                    <div class="stat-value">{{ $stats['total_events'] }}</div>
                    <div class="stat-label">Total Events</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value" style="color:#a78bfa;">{{ $stats['total_users'] }}</div>
                    <div class="stat-label">Total Users</div>
                </div>
            </div>

            <div class="card" style="padding:1.5rem;">
                <p style="font-family:'Syne',sans-serif;font-weight:700;font-size:0.95rem;color:#e6edf3;margin:0 0 1rem;">Quick Actions</p>
                <div style="display:flex;flex-wrap:wrap;gap:0.75rem;">
                    <a href="{{ route('users.index') }}" class="btn btn-primary">
                        <i data-lucide="users" style="width:15px;height:15px;"></i> Manage Users
                    </a>
                    <a href="{{ route('events.index') }}" class="btn btn-ghost">
                        <i data-lucide="calendar" style="width:15px;height:15px;"></i> Browse Events
                    </a>
                    <a href="{{ route('events.calendar') }}" class="btn btn-ghost">
                        <i data-lucide="grid" style="width:15px;height:15px;"></i> Calendar
                    </a>
                </div>
            </div>

        @elseif($role === 'organizer')
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:1rem;margin-bottom:2rem;">
                <div class="stat-card">
                    <div class="stat-value">{{ $stats['my_events'] }}</div>
                    <div class="stat-label">My Events</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">{{ $stats['upcoming_events'] }}</div>
                    <div class="stat-label">Upcoming</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value" style="color:#4ade80;">{{ $stats['confirmed'] }}</div>
                    <div class="stat-label">Confirmed</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value" style="color:#fbbf24;">{{ $stats['pending'] }}</div>
                    <div class="stat-label">Pending</div>
                </div>
            </div>

            @if($stats['pending'] > 0)
                <div class="alert alert-amber" style="margin-bottom:1.5rem;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:0.5rem;">
                    <span>⏳ <strong>{{ $stats['pending'] }}</strong> pending registration(s) awaiting your approval.</span>
                    <a href="{{ route('registrations.index') }}" style="color:#fbbf24;font-weight:600;text-decoration:none;font-size:0.85rem;">Review now →</a>
                </div>
            @endif

            <div class="card" style="padding:1.5rem;">
                <p style="font-family:'Syne',sans-serif;font-weight:700;font-size:0.95rem;color:#e6edf3;margin:0 0 1rem;">Quick Actions</p>
                <div style="display:flex;flex-wrap:wrap;gap:0.75rem;">
                    <a href="{{ route('events.create') }}" class="btn btn-primary">
                        <i data-lucide="plus" style="width:15px;height:15px;"></i> Create Event
                    </a>
                    <a href="{{ route('events.index') }}" class="btn btn-ghost">
                        <i data-lucide="calendar" style="width:15px;height:15px;"></i> Browse Events
                    </a>
                    <a href="{{ route('registrations.index') }}" class="btn btn-ghost">
                        <i data-lucide="clipboard-list" style="width:15px;height:15px;"></i> Registrations
                    </a>
                    <a href="{{ route('events.calendar') }}" class="btn btn-ghost">
                        <i data-lucide="grid" style="width:15px;height:15px;"></i> Calendar
                    </a>
                </div>
            </div>

        @else
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:1rem;margin-bottom:2rem;">
                <div class="stat-card">
                    <div class="stat-value">{{ $stats['events_attending'] }}</div>
                    <div class="stat-label">Attending</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value" style="color:#4ade80;">{{ $stats['confirmed'] }}</div>
                    <div class="stat-label">Confirmed</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value" style="color:#fbbf24;">{{ $stats['pending'] }}</div>
                    <div class="stat-label">Pending</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value" style="color:#60a5fa;">{{ $stats['upcoming'] }}</div>
                    <div class="stat-label">Upcoming</div>
                </div>
            </div>

            <div class="card" style="padding:1.5rem;">
                <p style="font-family:'Syne',sans-serif;font-weight:700;font-size:0.95rem;color:#e6edf3;margin:0 0 1rem;">Quick Actions</p>
                <div style="display:flex;flex-wrap:wrap;gap:0.75rem;">
                    <a href="{{ route('events.index') }}" class="btn btn-primary">
                        <i data-lucide="search" style="width:15px;height:15px;"></i> Browse Events
                    </a>
                    <a href="{{ route('registrations.index') }}" class="btn btn-ghost">
                        <i data-lucide="clipboard-list" style="width:15px;height:15px;"></i> My Registrations
                    </a>
                    <a href="{{ route('events.calendar') }}" class="btn btn-ghost">
                        <i data-lucide="grid" style="width:15px;height:15px;"></i> Calendar
                    </a>
                </div>
            </div>
        @endif

    </div>
</div>
@endsection