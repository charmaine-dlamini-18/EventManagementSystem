@extends('layouts.app')

@section('content')
<div style="padding:2rem 0;">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8" style="padding-left:1rem;padding-right:1rem;">

        <a href="{{ route('users.index') }}" class="back-link">
            <i data-lucide="arrow-left" style="width:15px;height:15px;"></i> Back to Users
        </a>

        <div class="page-header">
            <h1>Create User</h1>
            <p>Add a new user to the system</p>
        </div>

        <div class="card" style="padding:1.75rem;">
            <form method="POST" action="{{ route('users.store') }}" style="display:flex;flex-direction:column;gap:1.25rem;">
                @csrf

                <div>
                    <label class="form-label">Full Name <span style="color:#f87171;">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="Full name" class="form-input">
                    @error('name')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="form-label">Email <span style="color:#f87171;">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="email@example.com" class="form-input">
                    @error('email')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="form-label">Password <span style="color:#f87171;">*</span></label>
                    <input type="password" name="password" required placeholder="Min. 6 characters" class="form-input">
                    @error('password')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="form-label">Role <span style="color:#f87171;">*</span></label>
                    <select name="role" required class="form-select form-input">
                        <option value="" disabled {{ old('role') ? '' : 'selected' }}>Select a role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role }}" {{ old('role') === $role ? 'selected' : '' }}>{{ ucfirst($role) }}</option>
                        @endforeach
                    </select>
                    @error('role')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="divider"></div>

                <div style="display:flex;gap:0.75rem;">
                    <button type="submit" class="btn btn-primary">
                        <i data-lucide="plus" style="width:15px;height:15px;"></i> Create User
                    </button>
                    <a href="{{ route('users.index') }}" class="btn btn-ghost">Cancel</a>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection