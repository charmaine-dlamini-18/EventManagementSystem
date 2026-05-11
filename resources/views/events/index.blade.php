@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3 text-sm">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 text-sm">{{ session('error') }}</div>
        @endif
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Events</h1>
                <p class="text-sm text-gray-500 mt-0.5">Discover and join upcoming events</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('events.calendar') }}" class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 transition text-sm font-medium">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                    Calendar
                </a>
                @auth
                    @can('manage-events')
                        <a href="{{ route('events.create') }}" class="flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm font-medium">
                            <i data-lucide="plus" class="w-4 h-4"></i>
                            Create Event
                        </a>
                    @endcan
                @endauth
            </div>
        </div>

        <!-- Search & Filter Bar -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
            <form method="GET" action="{{ route('events.index') }}" class="flex flex-col md:flex-row gap-3">
                <div class="flex-1 relative">
                    <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search events..." class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <select name="status" class="px-3 py-2 border border-gray-200 rounded-lg text-sm text-gray-700 bg-white">
                    <option value="">All</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Upcoming</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Drafts</option>
                </select>
                <button type="submit" class="px-5 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm font-medium">Search</button>
                @if(request('search') || request('status'))
                    <a href="{{ route('events.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm font-medium">Clear</a>
                @endcan
            </form>
        </div>

        <!-- Results Count -->
        @if(request('search') || request('status'))
            <p class="text-sm text-gray-500">{{ $events->total() }} result(s) found</p>
        @endcan

        <!-- Events Grid -->
        @if($events->isEmpty())
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-12 text-center">
                <div class="w-14 h-14 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="calendar-x" class="w-7 h-7 text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No events found</h3>
                <p class="text-gray-500 text-sm mb-6">Try adjusting your search or filters.</p>
                <a href="{{ route('events.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm font-medium">View All Events</a>
            </div>
        @else
            <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                @foreach($events as $event)
                    <a href="{{ route('events.show', $event) }}" class="bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-lg hover:border-blue-200 transition-all duration-200 group overflow-hidden">
                        <div class="h-2 bg-green-600"></div>
                        <div class="p-5">
                            <div class="flex justify-between items-center mb-3">
                                <span class="px-2.5 py-1 text-xs font-medium rounded-full 
                                    @if($event->status === 'published') bg-green-100 text-green-700
                                    @elseif($event->status === 'draft') bg-amber-100 text-amber-700
                                    @else bg-red-100 text-red-700 @endif">
                                    {{ ucfirst($event->status) }}
                                </span>
                                @if($event->capacity)
                                    <span class="text-xs text-gray-400">{{ $event->registrations()->where('status', 'confirmed')->count() }}/{{ $event->capacity }} spots</span>
                                @endcan
                            </div>
                            <h2 class="text-lg font-semibold text-gray-900 mb-2 group-hover:text-green-700 transition-colors">{{ $event->title }}</h2>
                            <p class="text-sm text-gray-500 mb-4 line-clamp-2">{{ $event->description }}</p>
                            <div class="space-y-2 text-sm">
                                <div class="flex items-center gap-2 text-gray-600">
                                    <i data-lucide="map-pin" class="w-4 h-4 text-gray-400"></i>
                                    <span class="truncate">{{ $event->location }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-gray-600">
                                    <i data-lucide="clock" class="w-4 h-4 text-gray-400"></i>
                                    <span>{{ $event->start_date->format('M d, Y') }} at {{ $event->start_date->format('h:i A') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="px-5 py-3 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center">
                                    <span class="text-xs font-medium text-green-700">{{ substr($event->user->name, 0, 1) }}</span>
                                </div>
                                <span class="text-xs text-gray-500">{{ $event->user->name }}</span>
                            </div>
                            <span class="text-xs text-green-600 font-medium group-hover:text-green-800">Details →</span>
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="flex justify-center">{{ $events->withQueryString()->links() }}</div>
        @endcan
    </div>
</div>
@endsection