@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-6">
        
        <!-- Header -->
        <div>
            <a href="{{ route('events.show', $event) }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Back to Event
            </a>
            <h1 class="text-2xl font-bold text-gray-900 mt-4">Edit Event</h1>
            <p class="text-sm text-gray-500 mt-1">Update the event details</p>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <form method="POST" action="{{ route('events.update', $event) }}" class="space-y-5">
                @csrf
                @method('PATCH')

                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1.5">Event Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" id="title" value="{{ old('title', $event->title) }}" required
                        class="w-full border border-gray-200 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-slate-800 focus:border-transparent @error('title') border-red-500 @enderror">
                    @error('title')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1.5">Description <span class="text-red-500">*</span></label>
                    <textarea name="description" id="description" rows="4" required
                        class="w-full border border-gray-200 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-slate-800 focus:border-transparent @error('description') border-red-500 @enderror">{{ old('description', $event->description) }}</textarea>
                    @error('description')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-1.5">Location <span class="text-red-500">*</span></label>
                    <input type="text" name="location" id="location" value="{{ old('location', $event->location) }}" required
                        class="w-full border border-gray-200 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-slate-800 focus:border-transparent @error('location') border-red-500 @enderror">
                    @error('location')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1.5">Start <span class="text-red-500">*</span></label>
                        <input type="datetime-local" name="start_date" id="start_date" value="{{ old('start_date', $event->start_date->format('Y-m-d\TH:i')) }}" required
                            class="w-full border border-gray-200 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-slate-800 focus:border-transparent @error('start_date') border-red-500 @enderror">
                        @error('start_date')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1.5">End <span class="text-red-500">*</span></label>
                        <input type="datetime-local" name="end_date" id="end_date" value="{{ old('end_date', $event->end_date->format('Y-m-d\TH:i')) }}" required
                            class="w-full border border-gray-200 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-slate-800 focus:border-transparent @error('end_date') border-red-500 @enderror">
                        @error('end_date')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label for="capacity" class="block text-sm font-medium text-gray-700 mb-1.5">Capacity</label>
                    <input type="number" name="capacity" id="capacity" value="{{ old('capacity', $event->capacity) }}" min="1"
                        class="w-full border border-gray-200 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-slate-800 focus:border-transparent">
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1.5">Status <span class="text-red-500">*</span></label>
                    <select name="status" id="status" required class="w-full border border-gray-200 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-slate-800 focus:border-transparent">
                        <option value="draft" {{ old('status', $event->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status', $event->status) == 'published' ? 'selected' : '' }}>Published</option>
                        <option value="cancelled" {{ old('status', $event->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <div class="flex gap-3 pt-4 border-t border-gray-100">
                    <button type="submit" class="px-5 py-2.5 bg-slate-800 text-white font-medium rounded-lg hover:bg-slate-700 transition-colors text-sm">
                        Update Event
                    </button>
                    <a href="{{ route('events.show', $event) }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors text-sm">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
