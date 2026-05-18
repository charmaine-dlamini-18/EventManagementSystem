<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\ControllerCMS;
use App\Models\UserCMS;
use App\Rules\ValidRole;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserControllerCMS extends ControllerCMS
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     * Validates and saves the user's chosen role (attendee or organizer).
     * Admin accounts can only be created via database seeder.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . UserCMS::class],
            'role'     => ['required', new ValidRole],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'name.required'         => 'Please provide your name.',
            'name.max'              => 'Name must not exceed :max characters.',
            'email.required'        => 'An email address is required.',
            'email.email'           => 'Please enter a valid email address.',
            'email.unique'          => 'This email is already registered.',
            'email.max'             => 'Email must not exceed :max characters.',
            'role.required'         => 'Please select a role.',
            'password.required'     => 'Please enter a password.',
            'password.confirmed'    => 'Password confirmation does not match.',
        ]);

        $user = UserCMS::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'role'     => $request->role,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}