<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\ControllerCMS;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordControllerCMS extends ControllerCMS
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ], [
            'current_password.required'       => 'Please enter your current password.',
            'current_password.current_password' => 'The current password is incorrect.',
            'password.required'               => 'Please enter a new password.',
            'password.confirmed'              => 'Password confirmation does not match.',
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated');
    }
}
