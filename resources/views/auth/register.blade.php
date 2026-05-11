<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf
        <div>
            <x-input-label for="name" :value="__('Full Name')" class="text-sm font-medium text-gray-700" />
            <x-text-input id="name" class="block mt-1.5 w-full text-sm" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-sm font-medium text-gray-700" />
            <x-text-input id="email" class="block mt-1.5 w-full text-sm" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        {{-- Role Selection --}}
        <div>
            <x-input-label for="role" :value="__('I am registering as a...')" class="text-sm font-medium text-gray-700" />
            <select id="role" name="role" required
                class="block mt-1.5 w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                <option value="" disabled {{ old('role') ? '' : 'selected' }}>Select your role</option>
                <option value="attendee"  {{ old('role') === 'attendee'  ? 'selected' : '' }}>
                    🎟️ Attendee — I want to attend events
                </option>
                <option value="organizer" {{ old('role') === 'organizer' ? 'selected' : '' }}>
                    🎯 Organizer — I want to create and manage events
                </option>
            </select>
            <x-input-error :messages="$errors->get('role')" class="mt-1" />
            <p class="text-xs text-gray-400 mt-1">Admin accounts are created by the system administrator only.</p>
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')" class="text-sm font-medium text-gray-700" />
            <x-text-input id="password" class="block mt-1.5 w-full text-sm" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>
        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-sm font-medium text-gray-700" />
            <x-text-input id="password_confirmation" class="block mt-1.5 w-full text-sm" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
        </div>

        <x-primary-button class="w-full justify-center mt-6">{{ __('Create Account') }}</x-primary-button>
        <p class="text-center text-sm text-gray-500 mt-6">Already have an account? <a href="{{ route('login') }}" class="font-medium text-green-600 hover:text-green-800">Sign in</a></p>
    </form>
</x-guest-layout>