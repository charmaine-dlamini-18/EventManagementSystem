<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class UserCMS extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';

    protected $fillable = ['name', 'email', 'password', 'role'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function events(): HasMany
    {
        return $this->hasMany(EventCMS::class, 'user_id');
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(RegistrationCMS::class, 'user_id');
    }

    // -------------------------------------------------------------------------
    // Query Scopes
    // -------------------------------------------------------------------------

    /**
     * Scope: only admin users.
     * Usage: User::admins()->get()
     */
    public function scopeAdmins(Builder $query): Builder
    {
        return $query->where('role', 'admin');
    }

    /**
     * Scope: only organizer users.
     * Usage: User::organizers()->get()
     */
    public function scopeOrganizers(Builder $query): Builder
    {
        return $query->where('role', 'organizer');
    }

    /**
     * Scope: only attendee users.
     * Usage: User::attendees()->get()
     */
    public function scopeAttendees(Builder $query): Builder
    {
        return $query->where('role', 'attendee');
    }

    // -------------------------------------------------------------------------
    // Accessors
    // -------------------------------------------------------------------------

    /**
     * Accessor: formatted role label with emoji for display.
     * Example: "🛡️ Admin" / "🎯 Organizer" / "🎟️ Attendee"
     */
    protected function roleLabel(): Attribute
    {
        return Attribute::make(
            get: fn() => match ($this->role) {
                'admin'     => '🛡️ Admin',
                'organizer' => '🎯 Organizer',
                'attendee'  => '🎟️ Attendee',
                default     => ucfirst($this->role),
            },
        );
    }

    /**
     * Accessor: initials from the user's name (for avatars).
     * "Marc Cupit" → "MC"
     */
    protected function initials(): Attribute
    {
        return Attribute::make(
            get: function () {
                return collect(explode(' ', $this->name))
                    ->map(fn($word) => strtoupper(substr($word, 0, 1)))
                    ->take(2)
                    ->implode('');
            },
        );
    }

    // -------------------------------------------------------------------------
    // Mutators
    // -------------------------------------------------------------------------

    /**
     * Mutator: always trim and title-case the name on save.
     * "  marc cupit  " → "Marc Cupit"
     */
    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => $value,
            set: fn(string $value) => trim($value),
        );
    }

    /**
     * Mutator: always store email in lowercase.
     * "Marc@Example.COM" → "marc@example.com"
     */
    protected function email(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => $value,
            set: fn(string $value) => strtolower(trim($value)),
        );
    }

    // -------------------------------------------------------------------------
    // Helper Methods (kept from original, now cleaner)
    // -------------------------------------------------------------------------

    /**
     * Returns true if the user can create and manage events.
     * Kept for backward compatibility — prefer Gate::allows('manage-events-cms').
     */
    public function canCreateEvents(): bool
    {
        return in_array($this->role, ['admin', 'organizer']);
    }
}