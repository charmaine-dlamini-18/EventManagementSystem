@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <h1 class="text-2xl font-semibold text-gray-900 mb-6">Registration Details</h1>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="mb-6">
                    <h2 class="text-lg font-semibold">
                        <a href="{{ route('events.show', $registration->event) }}" class="hover:text-indigo-600">
                            {{ $registration->event->title }}
                        </a>
                    </h2>
                    <p class="text-gray-500">{{ $registration->event->start_date->format('M d, Y h:i A') }}</p>
                </div>

                <div class="mb-6">
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Attendee</h3>
                    <p class="font-medium">{{ $registration->user->name }}</p>
                    <p class="text-gray-500">{{ $registration->user->email }}</p>
                </div>

                @if($registration->notes)
                    <div class="mb-6">
                        <h3 class="text-sm font-medium text-gray-500 mb-2">Notes</h3>
                        <p>{{ $registration->notes }}</p>
                    </div>
                @endif

                <div class="mb-6">
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Status</h3>
                    <span class="px-2 py-1 text-xs rounded-full 
                        @if($registration->status === 'confirmed') bg-green-100 text-green-800
                        @elseif($registration->status === 'pending') bg-yellow-100 text-yellow-800
                        @else bg-red-100 text-red-800 @endif">
                        {{ ucfirst($registration->status) }}
                    </span>
                </div>

                @can('manage', $registration)
                    <form method="POST" action="{{ route('registrations.update', $registration) }}" class="space-y-4">
                        @csrf
                        @method('PATCH')
                        
                        <div>
                            <x-input-label for="status" value="Update Status" />
                            <select id="status" name="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="pending" {{ $registration->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ $registration->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="cancelled" {{ $registration->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>

                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                            Update Registration
                        </button>
                    </form>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection
