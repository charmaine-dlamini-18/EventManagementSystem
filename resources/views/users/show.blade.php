@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

        <a href="{{ route('users.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Users
        </a>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3 text-sm">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 text-sm">{{ session('error') }}</div>
        @endif

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="h-2 bg-purple-600"></div>
            <div class="px-6 py-5">
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center">
                            <span class="text-2xl font-bold text-purple-700">{{ $user->initials }}</span>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full
                                @if($user->role === 'admin') bg-purple-100 text-purple-700
                                @elseif($user->role === 'organizer') bg-blue-100 text-blue-700
                                @else bg-gray-100 text-gray-700 @endif">
                                {{ ucfirst($user->role) }}
                            </span>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('users.edit', $user) }}"
                           class="px-3 py-1.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                            <i data-lucide="pencil" class="w-4 h-4 inline"></i> Edit
                        </a>
                        @if($user->id !== Auth::id())
                            <form method="POST" action="{{ route('users.destroy', $user) }}"
                                  onsubmit="return confirm('Are you sure you want to delete {{ $user->name }}? This action cannot be undone.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="px-3 py-1.5 text-sm font-medium text-red-700 bg-red-50 rounded-lg hover:bg-red-100 transition">
                                    <i data-lucide="trash-2" class="w-4 h-4 inline"></i> Delete
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-100">
                <dl class="divide-y divide-gray-100">
                    <div class="px-6 py-4 grid grid-cols-3 gap-4">
                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                        <dd class="text-sm text-gray-900 col-span-2">{{ $user->email }}</dd>
                    </div>
                    <div class="px-6 py-4 grid grid-cols-3 gap-4">
                        <dt class="text-sm font-medium text-gray-500">Joined</dt>
                        <dd class="text-sm text-gray-900 col-span-2">{{ $user->created_at->format('F d, Y \a\t h:i A') }}</dd>
                    </div>
                    <div class="px-6 py-4 grid grid-cols-3 gap-4">
                        <dt class="text-sm font-medium text-gray-500">Email Verified</dt>
                        <dd class="text-sm text-gray-900 col-span-2">
                            @if($user->email_verified_at)
                                {{ $user->email_verified_at->format('F d, Y \a\t h:i A') }}
                            @else
                                <span class="text-amber-600">Not verified</span>
                            @endif
                        </dd>
                    </div>
                    <div class="px-6 py-4 grid grid-cols-3 gap-4">
                        <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                        <dd class="text-sm text-gray-900 col-span-2">{{ $user->updated_at->format('F d, Y \a\t h:i A') }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <div class="grid grid-cols-4 gap-4">
            <div class="bg-white rounded-xl border p-5 shadow-sm">
                <p class="text-2xl font-bold text-gray-900">{{ $stats['events'] }}</p>
                <p class="text-sm text-gray-500 mt-0.5">Events</p>
            </div>
            <div class="bg-white rounded-xl border p-5 shadow-sm">
                <p class="text-2xl font-bold text-gray-900">{{ $stats['registrations'] }}</p>
                <p class="text-sm text-gray-500 mt-0.5">Registrations</p>
            </div>
            <div class="bg-white rounded-xl border p-5 shadow-sm">
                <p class="text-2xl font-bold text-green-600">{{ $stats['confirmed'] }}</p>
                <p class="text-sm text-gray-500 mt-0.5">Confirmed</p>
            </div>
            <div class="bg-white rounded-xl border p-5 shadow-sm">
                <p class="text-2xl font-bold text-amber-500">{{ $stats['pending'] }}</p>
                <p class="text-sm text-gray-500 mt-0.5">Pending</p>
            </div>
        </div>

        <div class="flex gap-3">
            <a href="{{ route('users.edit', $user) }}" class="px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition">
                <i data-lucide="pencil" class="w-4 h-4 inline mr-1"></i> Edit User
            </a>
            <a href="{{ route('users.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition">
                Back to All Users
            </a>
        </div>

    </div>
</div>
@endsection