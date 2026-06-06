@extends('layouts.app')

@section('content')
<div style="padding:2rem 0;">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8" style="padding-left:1rem;padding-right:1rem;">

        <a href="{{ route('events.index') }}" class="back-link">
            <i data-lucide="arrow-left" style="width:15px;height:15px;"></i> Back to Events
        </a>

        <div class="page-header">
            <h1>Create New Event</h1>
            <p>Fill in the details below to publish or save as draft</p>
        </div>

        <div class="card" style="padding:1.75rem;">
            <form method="POST" action="{{ route('events.store') }}" style="display:flex;flex-direction:column;gap:1.25rem;">
                @csrf

                <div>
                    <label class="form-label">Title <span style="color:#f87171;">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" required placeholder="Event title" class="form-input">
                    @error('title')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="form-label">Description <span style="color:#f87171;">*</span></label>
                    <textarea name="description" rows="4" required placeholder="Describe your event…" class="form-input form-textarea" style="resize:vertical;">{{ old('description') }}</textarea>
                    @error('description')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="form-label">Location <span style="color:#f87171;">*</span></label>
                    <input type="text" name="location" value="{{ old('location') }}" required placeholder="Event location" class="form-input">
                    @error('location')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                    <div>
                        <label class="form-label">Start <span style="color:#f87171;">*</span></label>
                        <input type="datetime-local" name="start_date" value="{{ old('start_date') }}" required class="form-input">
                        @error('start_date')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="form-label">End <span style="color:#f87171;">*</span></label>
                        <input type="datetime-local" name="end_date" value="{{ old('end_date') }}" required class="form-input">
                        @error('end_date')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label class="form-label">Capacity</label>
                    <input type="number" name="capacity" value="{{ old('capacity') }}" min="1" placeholder="Optional — leave blank for unlimited" class="form-input">
                </div>

                <div>
                    <label class="form-label">Status <span style="color:#f87171;">*</span></label>
                    <select name="status" required class="form-select form-input">
                        <option value="draft"      {{ old('status') == 'draft'      ? 'selected' : '' }}>Draft</option>
                        <option value="published"  {{ old('status') == 'published'  ? 'selected' : '' }}>Published</option>
                    </select>
                </div>

                <div class="divider"></div>

                <div style="display:flex;gap:0.75rem;">
                    <button type="submit" class="btn btn-primary">
                        <i data-lucide="plus" style="width:15px;height:15px;"></i> Create Event
                    </button>
                    <a href="{{ route('events.index') }}" class="btn btn-ghost">Cancel</a>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection