@extends('layouts.app')

@section('content')
<div style="padding:2rem 0;">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" style="padding-left:1rem;padding-right:1rem;">

        <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:1rem;margin-bottom:1.5rem;">
            <div class="page-header" style="margin-bottom:0;">
                <h1>Event Calendar</h1>
                <p>View all events in calendar format</p>
            </div>
            <div style="display:flex;gap:0.6rem;">
                <a href="{{ route('events.index') }}" class="btn btn-ghost">
                    <i data-lucide="list" style="width:15px;height:15px;"></i> List View
                </a>
                @can('manage-events-cms')
                    <a href="{{ route('events.create') }}" class="btn btn-primary">
                        <i data-lucide="plus" style="width:15px;height:15px;"></i> Create Event
                    </a>
                @endcan
            </div>
        </div>

        <div class="card" style="padding:1.5rem;">
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
        --fc-border-color: rgba(255,255,255,0.08);
        --fc-button-bg-color: #1c2333;
        --fc-button-border-color: rgba(255,255,255,0.1);
        --fc-button-text-color: rgba(230,237,243,0.7);
        --fc-button-hover-bg-color: rgba(255,255,255,0.06);
        --fc-button-hover-border-color: rgba(255,255,255,0.15);
        --fc-button-active-bg-color: #16a34a;
        --fc-button-active-border-color: #16a34a;
        --fc-button-active-text-color: #fff;
        --fc-today-bg-color: rgba(22,163,74,0.07);
        --fc-event-bg-color: #16a34a;
        --fc-event-border-color: #16a34a;
        --fc-page-bg-color: transparent;
        --fc-neutral-bg-color: rgba(255,255,255,0.03);
        color: rgba(230,237,243,0.7);
    }
    .fc .fc-toolbar-title { font-family:'Syne',sans-serif; font-size:1.15rem; font-weight:800; color:#e6edf3; }
    .fc .fc-button { border-radius:7px !important; font-size:0.82rem !important; font-weight:500 !important; }
    .fc .fc-col-header-cell-cushion { font-size:0.72rem; font-weight:600; text-transform:uppercase; letter-spacing:0.07em; color:rgba(230,237,243,0.35); text-decoration:none; }
    .fc .fc-daygrid-day-number { font-size:0.8rem; color:rgba(230,237,243,0.5); text-decoration:none; }
    .fc .fc-day-today .fc-daygrid-day-number { color:#4ade80; font-weight:700; }
    .fc .fc-event { border-radius:5px !important; font-size:0.75rem !important; }
    .fc td, .fc th { border-color: rgba(255,255,255,0.06) !important; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
            initialView: 'dayGridMonth',
            headerToolbar: { left:'prev,next today', center:'title', right:'dayGridMonth,timeGridWeek,listWeek' },
            events: @json($calendarEvents),
            eventClick: function(info) { info.jsEvent.preventDefault(); window.location.href = info.event.url; },
            height: 'auto',
            dayMaxEvents: true,
        });
        calendar.render();
    });
</script>
@endpush
@endsection