@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
        
        <a href="{{ route('events.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Back to Events
        </a>

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="h-2 bg-green-600"></div>
            <div class="px-6 py-5">
                <div class="flex justify-between items-start mb-4">
                    <span class="px-3 py-1 text-sm font-medium rounded-full 
                        @if($event->status === 'published') bg-green-100 text-green-700
                        @elseif($event->status === 'draft') bg-amber-100 text-amber-700
                        @else bg-red-100 text-red-700 @endif">
                        {{ ucfirst($event->status) }}
                    </span>
                    
                    @can('update', $event)
                        <div class="flex gap-2">
                            <a href="{{ route('events.edit', $event) }}" class="px-3 py-1.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                                <i data-lucide="pencil" class="w-4 h-4 inline"></i> Edit
                            </a>
                            <form method="POST" action="{{ route('events.destroy', $event) }}" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="px-3 py-1.5 text-sm font-medium text-red-700 bg-red-50 rounded-lg hover:bg-red-100 transition" onclick="return confirm('Delete?')">
                                    <i data-lucide="trash-2" class="w-4 h-4 inline"></i> Delete
                                </button>
                            </form>
                        </div>
                    @endcan
                </div>

                <h1 class="text-2xl font-bold text-gray-900 mb-5">{{ $event->title }}</h1>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                    <div class="flex items-center gap-3 p-3 bg-blue-50 rounded-lg">
                        <i data-lucide="map-pin" class="w-5 h-5 text-green-600"></i>
                        <div>
                            <p class="text-xs text-green-600 font-medium">Location</p>
                            <p class="text-sm font-medium text-gray-900">{{ $event->location }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-3 bg-purple-50 rounded-lg">
                        <i data-lucide="calendar" class="w-5 h-5 text-purple-600"></i>
                        <div>
                            <p class="text-xs text-purple-600 font-medium">Date & Time</p>
                            <p class="text-sm font-medium text-gray-900">{{ $event->start_date->format('M d, Y') }} {{ $event->start_date->format('h:i A') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-3 bg-green-50 rounded-lg">
                        <i data-lucide="clock" class="w-5 h-5 text-green-600"></i>
                        <div>
                            <p class="text-xs text-green-600 font-medium">Duration</p>
                            <p class="text-sm font-medium text-gray-900">{{ $event->start_date->format('h:i A') }} - {{ $event->end_date->format('h:i A') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-3 bg-amber-50 rounded-lg">
                        <i data-lucide="user" class="w-5 h-5 text-amber-600"></i>
                        <div>
                            <p class="text-xs text-amber-600 font-medium">Organizer</p>
                            <p class="text-sm font-medium text-gray-900">{{ $event->user->name }}</p>
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <h2 class="text-base font-semibold text-gray-900 mb-3">About this event</h2>
                    <p class="text-sm text-gray-600 leading-relaxed whitespace-pre-wrap">{{ $event->description }}</p>
                </div>

                @if($event->capacity)
                    <div class="mb-6">
                        <p class="text-sm text-gray-500">Capacity: {{ $event->registrations()->where('status', 'confirmed')->count() }}/{{ $event->capacity }} spots filled</p>
                    </div>
                @endif
            </div>

            @auth
                @if($event->status === 'published')
                    <?php 
                        $currentUserId = auth()->id();
                        $userRegistration = \App\Models\Registration::where('user_id', $currentUserId)->where('event_id', $event->id)->first(); 
                        $isOrganizer = $currentUserId === $event->user_id;
                    ?>
                    <div class="px-6 py-5 border-t border-gray-100 bg-gray-50">
                        @if($isOrganizer)
                            <div class="flex items-center gap-3 text-green-700">
                                <span class="text-sm font-medium">You are the organizer of this event</span>
                            </div>
                        @elseif($userRegistration)
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Your Registration</p>
                                    <span class="inline-flex mt-1 px-2.5 py-1 rounded-full text-xs font-medium @if($userRegistration->status === 'confirmed') bg-green-100 text-green-700 @elseif($userRegistration->status === 'pending') bg-amber-100 text-amber-700 @else bg-red-100 text-red-700 @endif">{{ ucfirst($userRegistration->status) }}</span>
                                </div>
                                <form method="POST" action="{{ route('events.unregister', $event) }}">@csrf @method('DELETE')<button type="submit" class="px-4 py-2 text-sm font-medium text-red-700 bg-red-50 rounded-lg hover:bg-red-100 transition">Cancel Registration</button></form>
                            </div>
                        @else
                            <form method="POST" action="/events/{{ $event->id }}/register">
                                @csrf
                                <button type="submit" class="px-6 py-2.5 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition">
                                    Register for Event
                                </button>
                            </form>
                        @endif
                    </div>
                @endif
            @else
                <div class="px-6 py-5 border-t border-gray-100 bg-gray-50">
                    <p class="text-sm text-gray-600">Please <a href="{{ route('login') }}" class="font-medium text-green-600 hover:text-green-800">login</a> or <a href="{{ route('register') }}" class="font-medium text-green-600 hover:text-green-800">register</a> to register.</p>
                </div>
            @endauth

            @if(auth()->check() && (auth()->user()->role === 'admin' || auth()->id() === $event->user_id))
                <div class="px-6 py-5">
                    <h2 class="text-base font-semibold text-gray-900 mb-4">Registrations ({{ $registrations->count() }})</h2>
                    @if($registrations->isEmpty())
                        <div class="text-center py-8 bg-gray-50 rounded-lg border border-gray-100"><p class="text-gray-500 text-sm">No registrations yet.</p></div>
                    @else
                        <div class="space-y-3">
                            @foreach($registrations as $registration)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-100">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 bg-blue-100 rounded-full flex items-center justify-center"><span class="text-sm font-medium text-green-700">{{ substr($registration->user->name, 0, 1) }}</span></div>
                                        <div><p class="font-medium text-gray-900 text-sm">{{ $registration->user->name }}</p><p class="text-xs text-gray-500">{{ $registration->user->email }}</p></div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="px-2.5 py-1 text-xs rounded-full @if($registration->status === 'confirmed') bg-green-100 text-green-700 @elseif($registration->status === 'pending') bg-amber-100 text-amber-700 @else bg-red-100 text-red-700 @endif">{{ ucfirst($registration->status) }}</span>
                                        @if($registration->status === 'pending')
                                            <form method="POST" action="{{ route('registrations.approve', $registration) }}">@csrf<button type="submit" class="px-2.5 py-1 text-xs font-medium text-green-700 bg-green-50 rounded-lg hover:bg-green-100">Approve</button></form>
                                            <form method="POST" action="{{ route('registrations.decline', $registration) }}">@csrf<button type="submit" class="px-2.5 py-1 text-xs font-medium text-red-700 bg-red-50 rounded-lg hover:bg-red-100">Decline</button></form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection