@extends('layouts.app')

@section('content')
<div style="padding:2rem 0;">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8" style="padding-left:1rem;padding-right:1rem;">

        <a href="{{ route('users.index') }}" class="back-link">
            <i data-lucide="arrow-left" style="width:15px;height:15px;"></i> Back to Users
        </a>

        @if(session('success'))
            <div class="alert alert-success" style="margin-bottom:1.5rem;">{{ session('success') }}</div>
        @endif

        {{-- Profile card --}}
        <div class="card card-accent" style="margin-bottom:1.5rem;">
            <div style="padding:1.75rem;">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
                    <div style="display:flex;align-items:center;gap:1rem;">
                        <div class="avatar avatar-lg">{{ $user->initials }}</div>
                        <div>
                            <h1 style="font-family:'Syne',sans-serif;font-weight:800;font-size:1.4rem;color:#e6edf3;margin:0 0 0.4rem;letter-spacing:-0.02em;">{{ $user->name }}</h1>
                            <span class="badge {{ $user->role === 'admin' ? 'badge-purple' : ($user->role === 'organizer' ? 'badge-blue' : 'badge-gray') }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </div>
                    </div>
                    <div style="display:flex;gap:0.5rem;">
                        <a href="{{ route('users.edit', $user) }}" class="btn btn-ghost btn-sm">
                            <i data-lucide="pencil" style="width:13px;height:13px;"></i> Edit
                        </a>
                        @if($user->id !== Auth::id())
                            <form method="POST" action="{{ route('users.destroy', $user) }}"
                                  onsubmit="return confirm('Delete {{ $user->name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i data-lucide="trash-2" style="width:13px;height:13px;"></i> Delete
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <div style="border-top:1px solid rgba(255,255,255,0.06);">
                <div class="detail-row">
                    <div class="detail-key">Email</div>
                    <div class="detail-val">{{ $user->email }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-key">Joined</div>
                    <div class="detail-val">{{ $user->created_at->format('F d, Y \a\t h:i A') }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-key">Verified</div>
                    <div class="detail-val">
                        @if($user->email_verified_at)
                            <span style="color:#4ade80;">{{ $user->email_verified_at->format('F d, Y') }}</span>
                        @else
                            <span style="color:#fbbf24;">Not verified</span>
                        @endif
                    </div>
                </div>
                <div class="detail-row">
                    <div class="detail-key">Last Updated</div>
                    <div class="detail-val">{{ $user->updated_at->format('F d, Y \a\t h:i A') }}</div>
                </div>
            </div>
        </div>

        {{-- Stats --}}
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(130px,1fr));gap:1rem;margin-bottom:1.5rem;">
            <div class="stat-card">
                <div class="stat-value">{{ $stats['events'] }}</div>
                <div class="stat-label">Events</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $stats['registrations'] }}</div>
                <div class="stat-label">Registrations</div>
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

        <div style="display:flex;gap:0.75rem;">
            <a href="{{ route('users.edit', $user) }}" class="btn btn-primary">
                <i data-lucide="pencil" style="width:15px;height:15px;"></i> Edit User
            </a>
            <a href="{{ route('users.index') }}" class="btn btn-ghost">Back to All Users</a>
        </div>

    </div>
</div>
@endsection