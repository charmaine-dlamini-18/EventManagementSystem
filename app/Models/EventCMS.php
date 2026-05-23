<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EventCMS extends Model
{
    protected $table = 'events';

    protected $fillable = [
        'user_id', 'title', 'description', 'location',
        'start_date', 'end_date', 'capacity', 'status',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date'   => 'datetime',
        'capacity'   => 'integer',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserCMS::class, 'user_id');
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(RegistrationCMS::class, 'event_id');
    }

    // -------------------------------------------------------------------------
    // Query Scopes
    // Scopes let you reuse common WHERE clauses cleanly.
    // Usage: Event::published()->get()  /  Event::upcoming()->paginate(10)
    // -------------------------------------------------------------------------

    /**
     * Scope: only published events.
     * Usage: Event::published()->get()
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope: only upcoming (future) events.
     * Usage: Event::upcoming()->get()
     */
    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('start_date', '>=', now())->orderBy('start_date');
    }

    /**
     * Scope: only past events.
     * Usage: Event::past()->get()
     */
    public function scopePast(Builder $query): Builder
    {
        return $query->where('end_date', '<', now())->orderByDesc('end_date');
    }

    /**
     * Scope: filter by organizer (user_id).
     * Usage: Event::byOrganizer($userId)->get()
     */
    public function scopeByOrganizer(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope: only events that still have capacity.
     * Usage: Event::withAvailability()->get()
     */
    public function scopeWithAvailability(Builder $query): Builder
    {
        return $query->where(function (Builder $q) {
            // No capacity limit set — always available.
            $q->whereNull('capacity')
              // OR confirmed registrations are under capacity
              ->orWhereRaw(
                  'capacity > (SELECT COUNT(*) FROM registrations
                               WHERE registrations.event_id = events.id
                               AND registrations.status = ?)',
                  ['confirmed']
              );
        });
    }

    // -------------------------------------------------------------------------
    // Accessors
    // Read-only computed properties. Access like: $event->formatted_date
    // -------------------------------------------------------------------------

    /**
     * Accessor: formatted start date for display.
     * Example: "Monday, June 9, 2025 at 2:00 PM"
     */
    protected function formattedDate(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->start_date?->format('l, F j, Y \a\t g:i A'),
        );
    }

    /**
     * Accessor: short date range string for calendar/cards.
     * Example: "Jun 9 – Jun 11, 2025"
     */
    protected function dateRange(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->start_date?->format('M j') . ' – ' . $this->end_date?->format('M j, Y'),
        );
    }

    /**
     * Accessor: how many confirmed spots are still available.
     * Returns null if event has no capacity limit.
     */
    protected function spotsRemaining(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (! $this->capacity) {
                    return null; // Unlimited
                }
                $confirmed = $this->registrations()
                    ->where('status', 'confirmed')
                    ->count();
                return max(0, $this->capacity - $confirmed);
            }
        );
    }

    /**
     * Accessor: human-friendly status label with an emoji.
     * Example: "✅ Published"
     */
    protected function statusLabel(): Attribute
    {
        return Attribute::make(
            get: fn() => match ($this->status) {
                'published'  => '✅ Published',
                'draft'      => '📝 Draft',
                'cancelled'  => '❌ Cancelled',
                default      => ucfirst($this->status),
            },
        );
    }

    /**
     * Accessor: true if the event is currently ongoing.
     */
    protected function isOngoing(): Attribute
    {
        return Attribute::make(
            get: fn() => now()->between($this->start_date, $this->end_date),
        );
    }

    // -------------------------------------------------------------------------
    // Mutators
    // Transform data before it is saved to the database.
    // -------------------------------------------------------------------------

    /**
     * Mutator: always trim and title-case the event title on save.
     * "  my great EVENT  " → "My Great Event"
     */
    protected function title(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => $value,
            set: fn(string $value) => trim($value),
        );
    }

    /**
     * Mutator: always trim and normalise location whitespace on save.
     */
    protected function location(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => $value,
            set: fn(string $value) => trim($value),
        );
    }
}