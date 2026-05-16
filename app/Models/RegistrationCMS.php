<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegistrationCMS extends Model
{
    protected $table = 'registrations';

    protected $fillable = ['user_id', 'event_id', 'status', 'notes'];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserCMS::class, 'user_id');
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(EventCMS::class, 'event_id');
    }

    // -------------------------------------------------------------------------
    // Query Scopes
    // -------------------------------------------------------------------------

    /**
     * Scope: only pending registrations.
     * Usage: Registration::pending()->get()
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: only confirmed registrations.
     * Usage: Registration::confirmed()->get()
     */
    public function scopeConfirmed(Builder $query): Builder
    {
        return $query->where('status', 'confirmed');
    }

    /**
     * Scope: only cancelled registrations.
     * Usage: Registration::cancelled()->get()
     */
    public function scopeCancelled(Builder $query): Builder
    {
        return $query->where('status', 'cancelled');
    }

    /**
     * Scope: registrations for a specific event.
     * Usage: Registration::forEvent($eventId)->pending()->get()
     */
    public function scopeForEvent(Builder $query, int $eventId): Builder
    {
        return $query->where('event_id', $eventId);
    }

    /**
     * Scope: registrations belonging to a specific user.
     * Usage: Registration::forUser($userId)->confirmed()->get()
     */
    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    // -------------------------------------------------------------------------
    // Accessors
    // -------------------------------------------------------------------------

    /**
     * Accessor: status badge with emoji for display in views.
     * Example: "⏳ Pending" / "✅ Confirmed" / "❌ Cancelled"
     */
    protected function statusBadge(): Attribute
    {
        return Attribute::make(
            get: fn() => match ($this->status) {
                'pending'   => '⏳ Pending',
                'confirmed' => '✅ Confirmed',
                'cancelled' => '❌ Cancelled',
                default     => ucfirst($this->status),
            },
        );
    }

    /**
     * Accessor: true when the registration is still actionable (pending).
     * Useful for showing approve/decline buttons in views.
     */
    protected function isPending(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->status === 'pending',
        );
    }

    /**
     * Accessor: true when registration is confirmed.
     */
    protected function isConfirmed(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->status === 'confirmed',
        );
    }
}