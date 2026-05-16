<?php

namespace App\Http\Controllers;

use App\Models\UserCMS;
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

    public function index(Request $request): View
    {
        $query = UserCMS::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        return view('users.index', compact('users'));
    }

    public function create(): View
    {
        return view('users.create', ['roles' => $this->roles()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'role'     => ['required', Rule::in($this->roles())],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        UserCMS::create($validated);

        return redirect()->route('users.index')
            ->with('success', 'User created successfully.');
    }

    public function show(UserCMS $user): View
    {
        $stats = [
            'events'        => $user->events()->count(),
            'registrations' => $user->registrations()->count(),
            'confirmed'     => $user->registrations()->where('status', 'confirmed')->count(),
            'pending'       => $user->registrations()->where('status', 'pending')->count(),
        ];

        return view('users.show', compact('user', 'stats'));
    }

    public function edit(UserCMS $user): View
    {
        return view('users.edit', [
            'user'  => $user,
            'roles' => $this->roles(),
        ]);
    }

    public function update(Request $request, UserCMS $user): RedirectResponse
    {
        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role'  => ['required', Rule::in($this->roles())],
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => ['string', 'min:6']]);
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

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully.');
    }
}
