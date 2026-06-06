<section>
    <div style="margin-bottom:1.25rem;">
        <h2 style="font-family:'Syne',sans-serif;font-weight:700;font-size:1rem;color:#e6edf3;margin:0 0 0.25rem;">Profile Information</h2>
        <p style="font-size:0.83rem;color:rgba(230,237,243,0.35);margin:0;">Update your name and email address.</p>
    </div>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" style="display:flex;flex-direction:column;gap:1.1rem;">
        @csrf
        @method('patch')

        <div>
            <label class="form-label">Name</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" class="form-input">
            @error('name')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="form-label">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required autocomplete="username" class="form-input">
            @error('email')<p class="form-error">{{ $message }}</p>@enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div style="margin-top:0.6rem;padding:0.75rem;background:rgba(251,191,36,0.08);border:1px solid rgba(251,191,36,0.2);border-radius:8px;">
                    <p style="font-size:0.83rem;color:#fbbf24;margin:0;">
                        Your email is unverified.
                        <button form="send-verification" style="background:none;border:none;color:#fbbf24;text-decoration:underline;cursor:pointer;font-size:0.83rem;padding:0;">
                            Resend verification email.
                        </button>
                    </p>
                    @if (session('status') === 'verification-link-sent')
                        <p style="font-size:0.8rem;color:#4ade80;margin:0.4rem 0 0;">Verification link sent!</p>
                    @endif
                </div>
            @endif
        </div>

        <div style="display:flex;align-items:center;gap:1rem;">
            <button type="submit" class="btn btn-primary">
                <i data-lucide="save" style="width:15px;height:15px;"></i> Save Changes
            </button>
            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                   style="font-size:0.83rem;color:#4ade80;">Saved.</p>
            @endif
        </div>
    </form>
</section>