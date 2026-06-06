<section>
    <div style="margin-bottom:1.25rem;">
        <h2 style="font-family:'Syne',sans-serif;font-weight:700;font-size:1rem;color:#e6edf3;margin:0 0 0.25rem;">Update Password</h2>
        <p style="font-size:0.83rem;color:rgba(230,237,243,0.35);margin:0;">Use a long, random password to stay secure.</p>
    </div>

    <form method="post" action="{{ route('password.update') }}" style="display:flex;flex-direction:column;gap:1.1rem;">
        @csrf
        @method('put')

        <div>
            <label class="form-label">Current Password</label>
            <input id="update_password_current_password" type="password" name="current_password" autocomplete="current-password" class="form-input">
            @error('current_password', 'updatePassword')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="form-label">New Password</label>
            <input id="update_password_password" type="password" name="password" autocomplete="new-password" class="form-input">
            @error('password', 'updatePassword')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="form-label">Confirm Password</label>
            <input id="update_password_password_confirmation" type="password" name="password_confirmation" autocomplete="new-password" class="form-input">
            @error('password_confirmation', 'updatePassword')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        <div style="display:flex;align-items:center;gap:1rem;">
            <button type="submit" class="btn btn-primary">
                <i data-lucide="lock" style="width:15px;height:15px;"></i> Update Password
            </button>
            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                   style="font-size:0.83rem;color:#4ade80;">Saved.</p>
            @endif
        </div>
    </form>
</section>