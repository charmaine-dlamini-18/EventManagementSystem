@extends('layouts.app')

@section('content')
<div style="padding:2rem 0;">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8" style="padding-left:1rem;padding-right:1rem;">

        <div class="page-header">
            <h1>Profile Settings</h1>
            <p>Manage your account information and security</p>
        </div>

        <div style="display:flex;flex-direction:column;gap:1.25rem;">

            {{-- Profile info --}}
            <div class="card card-accent" style="padding:1.75rem;">
                @include('profile.partials.update-profile-information-form')
            </div>

            {{-- Password --}}
            <div class="card card-accent" style="padding:1.75rem;">
                @include('profile.partials.update-password-form')
            </div>

            {{-- Delete account --}}
            <div class="card" style="padding:1.75rem;border-color:rgba(239,68,68,0.15);">
                @include('profile.partials.delete-user-form')
            </div>

        </div>
    </div>
</div>
@endsection