@extends('layouts.app')

@section('content')
<div style="padding:2rem 0;">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" style="padding-left:1rem;padding-right:1rem;">

        @if(session('success'))
            <div class="alert alert-success" style="margin-bottom:1.5rem;">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error" style="margin-bottom:1.5rem;">{{ session('error') }}</div>
        @endif

        {{-- Header --}}
        <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:1rem;margin-bottom:1.5rem;">
            <div class="page-header" style="margin-bottom:0;">
                <h1>Events</h1>
                <p>Discover and join upcoming events</p>
            </div>
            <div style="display:flex;gap:0.6rem;flex-wrap:wrap;">
                <a href="{{ route('events.calendar') }}" class="btn btn-ghost">
                    <i data-lucide="calendar" style="width:15px;height:15px;"></i> Calendar
                </a>
                @auth
                    @can('manage-events-cms')
                        <a href="{{ route('events.create') }}" class="btn btn-primary">
                            <i data-lucide="plus" style="width:15px;height:15px;"></i> Create Event
                        </a>
                    @endcan
                @endauth
            </div>
        </div>

        {{-- Search bar --}}
        <div class="card" style="padding:1rem;margin-bottom:1.5rem;">
            <form method="GET" action="{{ route('events.index') }}" style="display:flex;flex-wrap:wrap;gap:0.65rem;">
                <div style="flex:1;min-width:180px;position:relative;">
                    <i data-lucide="search" style="position:absolute;left:0.75rem;top:50%;transform:translateY(-50%);width:15px;height:15px;color:rgba(230,237,243,0.3);"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search events…"
                           class="form-input" style="padding-left:2.25rem;">
                </div>
                <select name="status" class="form-select" style="width:auto;min-width:130px;">
                    <option value="">All statuses</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Upcoming</option>
                    <option value="draft"     {{ request('status') == 'draft'     ? 'selected' : '' }}>Drafts</option>
                </select>
                <button type="submit" class="btn btn-primary">Search</button>
                @if(request('search') || request('status'))
                    <a href="{{ route('events.index') }}" class="btn btn-ghost">Clear</a>
                @endif
            </form>
        </div>

        @if(request('search') || request('status'))
            <p style="font-size:0.8rem;color:rgba(230,237,243,0.35);margin-bottom:1rem;">{{ $events->total() }} result(s) found</p>
        @endif

        @if($events->isEmpty())
            <div class="card">
                <div class="empty-state">
                    <div class="empty-state-icon"><i data-lucide="calendar-x" style="width:24px;height:24px;"></i></div>
                    <h3>No events found</h3>
                    <p>Try adjusting your search or filters.</p>
                    <a href="{{ route('events.index') }}" class="btn btn-primary">View All Events</a>
                </div>
            </div>
        @else
            <div style="display:grid;gap:1rem;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));">
                @foreach($events as $event)
                    <a href="{{ route('events.show', $event) }}" style="text-decoration:none;display:flex;flex-direction:column;" class="card card-accent">
                        <div style="padding:1.25rem 1.25rem 1rem;flex:1;">
                            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.85rem;">
                                <span class="badge {{ $event->status === 'published' ? 'badge-green' : ($event->status === 'draft' ? 'badge-amber' : 'badge-red') }}">
                                    {{ ucfirst($event->status) }}
                                </span>
                                @if($event->capacity)
                                    <span style="font-size:0.75rem;color:rgba(230,237,243,0.3);">
                                        {{ $event->registrations()->where('status','confirmed')->count() }}/{{ $event->capacity }} spots
                                    </span>
                                @endif
                            </div>
                            <h2 style="font-family:'Syne',sans-serif;font-weight:700;font-size:1.05rem;color:#e6edf3;margin:0 0 0.5rem;letter-spacing:-0.02em;">{{ $event->title }}</h2>
                            <p style="font-size:0.83rem;color:rgba(230,237,243,0.4);margin:0 0 1rem;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">{{ $event->description }}</p>
                            <div style="display:flex;flex-direction:column;gap:0.4rem;">
                                <div style="display:flex;align-items:center;gap:0.5rem;font-size:0.8rem;color:rgba(230,237,243,0.4);">
                                    <i data-lucide="map-pin" style="width:13px;height:13px;flex-shrink:0;"></i>
                                    <span style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $event->location }}</span>
                                </div>
                                <div style="display:flex;align-items:center;gap:0.5rem;font-size:0.8rem;color:rgba(230,237,243,0.4);">
                                    <i data-lucide="clock" style="width:13px;height:13px;flex-shrink:0;"></i>
                                    <span>{{ $event->start_date->format('M d, Y') }} at {{ $event->start_date->format('h:i A') }}</span>
                                </div>
                            </div>
                        </div>
                        <div style="padding:0.75rem 1.25rem;border-top:1px solid rgba(255,255,255,0.06);display:flex;align-items:center;justify-content:space-between;">
                            <div style="display:flex;align-items:center;gap:0.5rem;">
                                <div class="avatar" style="width:26px;height:26px;border-radius:6px;font-size:0.7rem;">{{ substr($event->user->name,0,1) }}</div>
                                <span style="font-size:0.78rem;color:rgba(230,237,243,0.35);">{{ $event->user->name }}</span>
                            </div>
                            <span style="font-size:0.78rem;color:#4ade80;font-weight:500;">Details →</span>
                        </div>
                    </a>
                @endforeach
            </div>
            <div style="display:flex;justify-content:center;margin-top:2rem;">{{ $events->withQueryString()->links() }}</div>
        @endif

    </div>
</div>
@endsection