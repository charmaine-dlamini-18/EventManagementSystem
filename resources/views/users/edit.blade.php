@extends('layouts.app')

@section('content')
<div style="padding:2rem 0;">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8" style="padding-left:1rem;padding-right:1rem;">

        <a href="{{ route('users.show', $user) }}" class="back-link">
            <i data-lucide="arrow-left" style="width:15px;height:15px;"></i> Back to User
        </a>

        <div class="page-header">
            <h1>Edit User</h1>
            <p>Update details for {{ $user->name }}</p>
        </div>

        <div class="card" style="padding:1.75rem;">
            <form method="POST" action="{{ route('users.update', $user) }}" style="display:flex;flex-direction:column;gap:1.25rem;">
                @csrf
                @method('PATCH')

                <div>
                    <label class="form-label">Full Name <span style="color:#f87171;">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="form-input">
                    @error('name')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="form-label">Email <span style="color:#f87171;">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="form-input">
                    @error('email')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="form-label">New Password</label>
                    <input type="password" name="password" placeholder="Leave blank to keep current password" class="form-input">
                    <p style="font-size:0.75rem;color:rgba(230,237,243,0.3);margin-top:0.3rem;">Min. 6 characters. Leave empty to keep the current password.</p>
                    @error('password')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="form-label">Role <span style="color:#f87171;">*</span></label>
                    <select name="role" required class="form-select form-input">
                        @foreach($roles as $role)
                            <option value="{{ $role }}" {{ old('role', $user->role) === $role ? 'selected' : '' }}>{{ ucfirst($role) }}</option>
                        @endforeach
                    </select>
                    @error('role')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="divider"></div>

                <div style="display:flex;gap:0.75rem;">
                    <button type="submit" class="btn btn-primary">
                        <i data-lucide="save" style="width:15px;height:15px;"></i> Update User
                    </button>
                    <a href="{{ route('users.show', $user) }}" class="btn btn-ghost">Cancel</a>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection