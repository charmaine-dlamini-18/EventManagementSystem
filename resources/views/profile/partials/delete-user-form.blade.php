<section>
    <div style="margin-bottom:1.25rem;">
        <h2 style="font-family:'Syne',sans-serif;font-weight:700;font-size:1rem;color:#f87171;margin:0 0 0.25rem;">Delete Account</h2>
        <p style="font-size:0.83rem;color:rgba(230,237,243,0.35);margin:0;">Once deleted, all your data will be permanently removed. This cannot be undone.</p>
    </div>

    <button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="btn btn-danger">
        <i data-lucide="trash-2" style="width:15px;height:15px;"></i> Delete Account
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" style="padding:1.75rem;">
            @csrf
            @method('delete')

            <h2 style="font-family:'Syne',sans-serif;font-weight:800;font-size:1.15rem;color:#e6edf3;margin:0 0 0.5rem;">Are you sure?</h2>
            <p style="font-size:0.875rem;color:rgba(230,237,243,0.45);margin:0 0 1.25rem;">
                This will permanently delete your account and all associated data. Enter your password to confirm.
            </p>

            <div style="margin-bottom:1.25rem;">
                <label class="form-label">Password</label>
                <input type="password" name="password" placeholder="Your current password" class="form-input" style="max-width:320px;">
                @error('password', 'userDeletion')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div style="display:flex;justify-content:flex-end;gap:0.75rem;">
                <button type="button" x-on:click="$dispatch('close')" class="btn btn-ghost">Cancel</button>
                <button type="submit" class="btn btn-danger">
                    <i data-lucide="trash-2" style="width:15px;height:15px;"></i> Delete Account
                </button>
            </div>
        </form>
    </x-modal>
</section>