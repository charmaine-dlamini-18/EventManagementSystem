<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

// This controller manages user profile actions.
class ProfileControllerCMS extends ControllerCMS
{
  // This shows where users edit their profile.
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }
      // This is where users update their profile
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }
           // Save the updated information.
        $request->user()->save();
            // Return to the profile page with a success message.
        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function destroy(Request $request): RedirectResponse
    {
        // Check if the correct password was entered.
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ], [
            'password.required'        => 'Please enter your password.',
            'password.current_password' => 'Your password is incorrect.',
        ]);

        $user = $request->user();
        
        // Log the user out.
        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // Redirect to the home page.
        return Redirect::to('/');
    }
}
