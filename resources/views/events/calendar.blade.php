@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Event Calendar</h1>
                <p class="text-sm text-gray-500 mt-0.5">View all events in calendar format</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('events.index') }}" class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-sm font-medium">
                    List View
                </a>
                @can('manage-events-cms')
                    <a href="{{ route('events.create') }}" class="flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm font-medium">
                        + Create Event
                    </a>
                @endcan
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 md:p-6">
            <div id="calendar"></div>
        </div>
    </div>
</div>

@php
$calendarEvents = $events->map(fn($e) => [
    'title' => $e->title,
    'start' => $e->start_date->toIso8601String(),
    'end'   => $e->end_date->toIso8601String(),
    'url'   => route('events.show', $e->id),
]);
@endphp

@push('styles')
<style>
    .fc {
        --fc-border-color: #e5e7eb;
        --fc-button-bg-color: #f9fafb;
        --fc-button-border-color: #d1d5db;
        --fc-button-text-color: #374151;
        --fc-button-hover-bg-color: #f3f4f6;
        --fc-button-hover-border-color: #9ca3af;
        --fc-button-active-bg-color: #1f2937;
        --fc-button-active-border-color: #1f2937;
        --fc-today-bg-color: #f9fafb;
        --fc-event-bg-color: #1f2937;
        --fc-event-border-color: #1f2937;
    }
    .fc .fc-toolbar-title { font-size: 1.25rem; font-weight: 600; }
    .fc .fc-button { border-radius: 0.5rem; font-weight: 500; font-size: 0.875rem; }
    .fc .fc-daygrid-day-number { padding: 4px; font-weight: 500; }
    .fc .fc-event { border-radius: 6px; font-size: 0.75rem; }
    .fc .fc-col-header-cell-cushion { font-weight: 600; font-size: 0.75rem; text-transform: uppercase; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var events = @json($calendarEvents);
        
        var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
            initialView: 'dayGridMonth',
            headerToolbar: { left: 'prev,next today', center: 'title', right: 'dayGridMonth,timeGridWeek,listWeek' },
            events: events,
            eventClick: function(info) { info.jsEvent.preventDefault(); window.location.href = info.event.url; },
            height: 'auto', dayMaxEvents: true
        });
        calendar.render();
    });
</script>
@endpush
@endsection