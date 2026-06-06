@extends('layouts.app')

@section('content')
<div style="padding:2rem 0;">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8" style="padding-left:1rem;padding-right:1rem;">

        <a href="{{ route('events.index') }}" class="back-link">
            <i data-lucide="arrow-left" style="width:15px;height:15px;"></i> Back to Events
        </a>

        @if(session('success'))
            <div class="alert alert-success" style="margin-bottom:1.5rem;">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error" style="margin-bottom:1.5rem;">{{ session('error') }}</div>
        @endif

        <div class="card card-accent">
            <div style="padding:1.75rem 1.75rem 1.5rem;">

                {{-- Status + Actions --}}
                <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:1rem;margin-bottom:1.25rem;">
                    <span class="badge {{ $event->status === 'published' ? 'badge-green' : ($event->status === 'draft' ? 'badge-amber' : 'badge-red') }}">
                        {{ $event->status_label }}
                    </span>
                    @can('update', $event)
                        <div style="display:flex;gap:0.5rem;">
                            <a href="{{ route('events.edit', $event) }}" class="btn btn-ghost btn-sm">
                                <i data-lucide="pencil" style="width:13px;height:13px;"></i> Edit
                            </a>
                            <form method="POST" action="{{ route('events.destroy', $event) }}"
                                  onsubmit="return confirm('Delete this event?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i data-lucide="trash-2" style="width:13px;height:13px;"></i> Delete
                                </button>
                            </form>
                        </div>
                    @endcan
                </div>

                <h1 style="font-family:'Syne',sans-serif;font-weight:800;font-size:1.65rem;color:#e6edf3;letter-spacing:-0.03em;margin:0 0 1.5rem;">{{ $event->title }}</h1>

                {{-- Info tiles --}}
                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:0.75rem;margin-bottom:1.5rem;">
                    <div class="info-tile">
                        <div class="info-tile-icon" style="background:rgba(22,163,74,0.15);">
                            <i data-lucide="map-pin" style="width:16px;height:16px;color:#4ade80;"></i>
                        </div>
                        <div>
                            <div class="info-tile-label">Location</div>
                            <div class="info-tile-value">{{ $event->location }}</div>
                        </div>
                    </div>
                    <div class="info-tile">
                        <div class="info-tile-icon" style="background:rgba(96,165,250,0.15);">
                            <i data-lucide="calendar" style="width:16px;height:16px;color:#60a5fa;"></i>
                        </div>
                        <div>
                            <div class="info-tile-label">Date & Time</div>
                            <div class="info-tile-value">{{ $event->formatted_date }}</div>
                        </div>
                    </div>
                    <div class="info-tile">
                        <div class="info-tile-icon" style="background:rgba(167,139,250,0.15);">
                            <i data-lucide="clock" style="width:16px;height:16px;color:#a78bfa;"></i>
                        </div>
                        <div>
                            <div class="info-tile-label">Duration</div>
                            <div class="info-tile-value">{{ $event->date_range }}</div>
                        </div>
                    </div>
                    <div class="info-tile">
                        <div class="info-tile-icon" style="background:rgba(251,191,36,0.15);">
                            <i data-lucide="user" style="width:16px;height:16px;color:#fbbf24;"></i>
                        </div>
                        <div>
                            <div class="info-tile-label">Organizer</div>
                            <div class="info-tile-value">{{ $event->user->name }}</div>
                        </div>
                    </div>
                </div>

                {{-- Description --}}
                <div style="margin-bottom:1.25rem;">
                    <p style="font-size:0.72rem;color:rgba(230,237,243,0.35);text-transform:uppercase;letter-spacing:0.08em;margin-bottom:0.6rem;">About this event</p>
                    <p style="font-size:0.9rem;color:rgba(230,237,243,0.65);line-height:1.7;white-space:pre-wrap;">{{ $event->description }}</p>
                </div>

                {{-- Capacity --}}
                @if($event->capacity)
                    @php $spotsRemaining = $event->spots_remaining; @endphp
                    <div style="display:flex;align-items:center;gap:0.5rem;font-size:0.85rem;color:rgba(230,237,243,0.4);">
                        <i data-lucide="users" style="width:14px;height:14px;"></i>
                        {{ $event->capacity - $spotsRemaining }}/{{ $event->capacity }} spots filled —
                        @if($spotsRemaining === 0)
                            <span style="color:#f87171;font-weight:600;">Full</span>
                        @else
                            <span style="color:#4ade80;font-weight:600;">{{ $spotsRemaining }} left</span>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Registration Panel --}}
            @auth
                @if($event->status === 'published')
                    @php
                        $userRegistration = \App\Models\RegistrationCMS::where('user_id', auth()->id())->where('event_id', $event->id)->first();
                        $isOrganizer = auth()->id() === $event->user_id;
                    @endphp
                    <div style="padding:1.25rem 1.75rem;border-top:1px solid rgba(255,255,255,0.06);background:rgba(255,255,255,0.02);">
                        @if($isOrganizer)
                            <p style="font-size:0.875rem;color:#4ade80;font-weight:500;">✓ You are the organizer of this event.</p>
                        @elseif($userRegistration)
                            <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
                                <div>
                                    <p style="font-size:0.75rem;color:rgba(230,237,243,0.35);text-transform:uppercase;letter-spacing:0.07em;margin-bottom:0.4rem;">Your Registration</p>
                                    <span class="badge {{ $userRegistration->status === 'confirmed' ? 'badge-green' : ($userRegistration->status === 'pending' ? 'badge-amber' : 'badge-red') }}">
                                        {{ $userRegistration->status_badge }}
                                    </span>
                                </div>
                                @if($userRegistration->status !== 'cancelled')
                                    <form method="POST" action="{{ route('events.unregister', $event) }}" onsubmit="return confirm('Cancel your registration?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Cancel Registration</button>
                                    </form>
                                @endif
                            </div>
                        @elseif($event->is_ongoing || $event->spots_remaining !== 0)
                            <form method="POST" action="{{ route('events.register', $event) }}">
                                @csrf
                                <button type="submit" class="btn btn-primary">
                                    <i data-lucide="check-circle" style="width:15px;height:15px;"></i> Register for Event
                                </button>
                            </form>
                        @else
                            <p style="font-size:0.875rem;color:#f87171;font-weight:500;">This event is at full capacity.</p>
                        @endif
                    </div>
                @endif
            @else
                <div style="padding:1.25rem 1.75rem;border-top:1px solid rgba(255,255,255,0.06);background:rgba(255,255,255,0.02);">
                    <p style="font-size:0.875rem;color:rgba(230,237,243,0.4);">
                        Please <a href="{{ route('login') }}" style="color:#4ade80;font-weight:500;text-decoration:none;">login</a>
                        or <a href="{{ route('register') }}" style="color:#4ade80;font-weight:500;text-decoration:none;">register</a> to sign up for this event.
                    </p>
                </div>
            @endauth

            {{-- Registrations list (organizer/admin) --}}
            @can('manageRegistrations', $event)
                <div style="padding:1.5rem 1.75rem;border-top:1px solid rgba(255,255,255,0.06);">
                    <p style="font-family:'Syne',sans-serif;font-weight:700;font-size:0.95rem;color:#e6edf3;margin:0 0 1rem;">
                        Registrations <span style="font-family:'DM Sans',sans-serif;font-weight:400;font-size:0.8rem;color:rgba(230,237,243,0.35);">({{ $registrations->count() }})</span>
                    </p>
                    @if($registrations->isEmpty())
                        <p style="font-size:0.875rem;color:rgba(230,237,243,0.3);text-align:center;padding:1.5rem 0;">No registrations yet.</p>
                    @else
                        <div style="display:flex;flex-direction:column;gap:0.5rem;">
                            @foreach($registrations as $registration)
                                <div style="display:flex;align-items:center;justify-content:space-between;padding:0.85rem 1rem;background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);border-radius:10px;flex-wrap:wrap;gap:0.75rem;">
                                    <div style="display:flex;align-items:center;gap:0.75rem;">
                                        <div class="avatar">{{ $registration->user->initials }}</div>
                                        <div>
                                            <p style="font-size:0.875rem;font-weight:500;color:#e6edf3;margin:0;">{{ $registration->user->name }}</p>
                                            <p style="font-size:0.75rem;color:rgba(230,237,243,0.35);margin:0;">{{ $registration->user->email }}</p>
                                        </div>
                                    </div>
                                    <div style="display:flex;align-items:center;gap:0.5rem;">
                                        <span class="badge {{ $registration->status === 'confirmed' ? 'badge-green' : ($registration->status === 'pending' ? 'badge-amber' : 'badge-red') }}">
                                            {{ $registration->status_badge }}
                                        </span>
                                        @if($registration->is_pending)
                                            <form method="POST" action="{{ route('registrations.approve', $registration) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-ghost btn-sm" style="color:#4ade80;border-color:rgba(74,222,128,0.2);">Approve</button>
                                            </form>
                                            <form method="POST" action="{{ route('registrations.decline', $registration) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm">Decline</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endcan
        </div>

    </div>
</div>
@endsection