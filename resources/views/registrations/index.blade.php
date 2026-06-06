@extends('layouts.app')

@section('content')
<div style="padding:2rem 0;">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8" style="padding-left:1rem;padding-right:1rem;">

        <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:1rem;margin-bottom:1.5rem;">
            <div class="page-header" style="margin-bottom:0;">
                @if(in_array(auth()->user()->role, ['admin', 'organizer']))
                    <h1>Event Registrations</h1>
                    <p>Approve or decline attendees for your events</p>
                @else
                    <h1>My Registrations</h1>
                    <p>Your event registrations</p>
                @endif
            </div>
            <a href="{{ route('events.index') }}" class="btn btn-ghost">
                <i data-lucide="search" style="width:15px;height:15px;"></i> Browse Events
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success" style="margin-bottom:1.5rem;">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error" style="margin-bottom:1.5rem;">{{ session('error') }}</div>
        @endif

        @if($registrations->isEmpty())
            <div class="card">
                <div class="empty-state">
                    <div class="empty-state-icon"><i data-lucide="clipboard-x" style="width:24px;height:24px;"></i></div>
                    <h3>No registrations yet</h3>
                    <p>Browse events to get started.</p>
                    <a href="{{ route('events.index') }}" class="btn btn-primary">Browse Events</a>
                </div>
            </div>
        @else
            <div style="display:flex;flex-direction:column;gap:0.75rem;">
                @foreach($registrations as $registration)
                    <div class="card" style="padding:1.25rem 1.5rem;">
                        <div style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
                            <div style="flex:1;min-width:200px;">
                                <div style="display:flex;align-items:center;gap:0.6rem;flex-wrap:wrap;margin-bottom:0.5rem;">
                                    <a href="{{ route('events.show', $registration->event) }}" style="font-family:'Syne',sans-serif;font-weight:700;font-size:1rem;color:#e6edf3;text-decoration:none;">
                                        {{ $registration->event->title }}
                                    </a>
                                    <span class="badge {{ $registration->status === 'confirmed' ? 'badge-green' : ($registration->status === 'pending' ? 'badge-amber' : 'badge-red') }}">
                                        {{ $registration->status_badge }}
                                    </span>
                                </div>

                                @if(in_array(auth()->user()->role, ['admin', 'organizer']))
                                    <div style="display:flex;align-items:center;gap:0.4rem;font-size:0.8rem;color:rgba(230,237,243,0.4);margin-bottom:0.4rem;">
                                        <i data-lucide="user" style="width:13px;height:13px;"></i>
                                        {{ $registration->user->name }} — {{ $registration->user->email }}
                                    </div>
                                @endif

                                <div style="display:flex;flex-wrap:wrap;gap:1rem;">
                                    <div style="display:flex;align-items:center;gap:0.4rem;font-size:0.8rem;color:rgba(230,237,243,0.35);">
                                        <i data-lucide="calendar" style="width:13px;height:13px;"></i>
                                        {{ $registration->event->start_date->format('M d, Y') }}
                                    </div>
                                    <div style="display:flex;align-items:center;gap:0.4rem;font-size:0.8rem;color:rgba(230,237,243,0.35);">
                                        <i data-lucide="map-pin" style="width:13px;height:13px;"></i>
                                        {{ Str::limit($registration->event->location, 30) }}
                                    </div>
                                </div>
                            </div>

                            <div style="display:flex;gap:0.5rem;flex-wrap:wrap;align-items:center;">
                                <a href="{{ route('events.show', $registration->event) }}" class="btn btn-ghost btn-sm">View Event</a>

                                @if($registration->is_pending)
                                    @can('approve', $registration)
                                        <form method="POST" action="{{ route('registrations.approve', $registration) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-sm" style="background:rgba(74,222,128,0.1);color:#4ade80;border:1px solid rgba(74,222,128,0.2);">Approve</button>
                                        </form>
                                        <form method="POST" action="{{ route('registrations.decline', $registration) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm">Decline</button>
                                        </form>
                                    @endcan
                                @endif

                                @can('cancel', $registration)
                                    @if($registration->status !== 'cancelled')
                                        <form method="POST" action="{{ route('events.unregister', $registration->event) }}"
                                              onsubmit="return confirm('Cancel your registration?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Cancel</button>
                                        </form>
                                    @endif
                                @endcan
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div style="display:flex;justify-content:center;margin-top:1.5rem;">{{ $registrations->links() }}</div>
        @endif

    </div>
</div>
@endsection