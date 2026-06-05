<?php

namespace App\Http\Controllers;

use App\Models\UserCMS;
use App\Rules\ValidRole;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserControllerCMS extends ControllerCMS
{
    private function roles(): array
    {
        return ['admin', 'organizer', 'attendee'];
    }
         // Return the list of user roles.
    public function index(Request $request): View
    {
        $query = UserCMS::query();
         // Search users by name or email.
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                  ->orWhere('email', 'like', '%'.$search.'%');
            });
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        return view('users.index', compact('users'));
    }

    public function create(): View
    {
        return view('users.create', ['roles' => $this->roles()]);
    }
        // Save a new user to the database.
    public function store(Request $request): RedirectResponse
    {
           // Validate the user input.
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', new ValidRole()],
        ], [
            'name.required' => 'Please provide a name.',
            'name.max' => 'Name must not exceed :max characters.',
            'email.required' => 'An email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already registered.',
            'email.max' => 'Email must not exceed :max characters.',
            'password.required' => 'Please enter password.',
            'password.min' => 'Password must be at least :min characters.',
            'role.required' => 'Please select a role.',
        ]);
             // Encrypt the password.
        $validated['password'] = Hash::make($validated['password']);

        UserCMS::create($validated);

        return redirect()->route('users.index')
            ->with('success', 'User created successfully.');
    }
        // Show information and statistics for one user.
    public function show(UserCMS $user): View
    {
         // Count the user's events and registrations.
        $stats = [
            'events' => $user->events()->count(),
            'registrations' => $user->registrations()->count(),
            'confirmed' => $user->registrations()->where('status', 'confirmed')->count(),
            'pending' => $user->registrations()->where('status', 'pending')->count(),
        ];

        return view('users.show', compact('user', 'stats'));
    }

    public function edit(UserCMS $user): View
    {
        return view('users.edit', [
            'user' => $user,
            'roles' => $this->roles(),
        ]);
    }

    public function update(Request $request, UserCMS $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', new ValidRole()],
        ], [
            'name.required' => 'Please provide a name.',
            'name.max' => 'Name must not exceed :max characters.',
            'email.required' => 'An email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already in use.',
            'email.max' => 'Email must not exceed :max characters.',
            'role.required' => 'Please select a role.',
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => ['string', 'min:6']], [
                'password.min' => 'Password must be at least :min characters.',
            ]);
            $validated['password'] = Hash::make($request->password);
        }

        if ($user->role === 'admin' && $validated['role'] !== 'admin' && UserCMS::where('role', 'admin')->count() <= 1) {
            return back()->with('error', 'Cannot change the role of the last admin account.')->withInput();
        }

        $user->update($validated);

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(UserCMS $user): RedirectResponse
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        if ($user->role === 'admin' && UserCMS::where('role', 'admin')->count() <= 1) {
            return back()->with('error', 'Cannot delete the last admin account.');
        }
        
        // Remove the user from the database.
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully.');
    }
}
