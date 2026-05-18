<?php

namespace App\Observers;

use App\Models\EventCMS;
use Illuminate\Support\Facades\Log;

/**
 * EventObserver
 *
 * Listens to the Eloquent lifecycle of the Event model and reacts automatically.
 * Registered in AppServiceProvider via Event::observe(EventObserver::class).
 *
 * Lifecycle hooks available:
 *   creating, created, updating, updated, saving, saved,
 *   deleting, deleted, restoring, restored, forceDeleted
 */
class EventObserver
{
    /**
     * Fires BEFORE a new event is saved for the first time.
     * Ensures status always defaults to 'draft' if not explicitly set.
     */
    public function creating(EventCMS $event): void
    {
        if (empty($event->status)) {
            $event->status = 'draft';
        }
    }

    /**
     * Fires AFTER a new event is successfully created.
     * Good place for logging or side-effects on creation.
     */
    public function created(EventCMS $event): void
    {
        Log::info('Event created', [
            'event_id'   => $event->id,
            'title'      => $event->title,
            'organizer'  => $event->user_id,
            'status'     => $event->status,
        ]);
    }

    /**
     * Fires BEFORE an existing event is updated.
     * Use $event->isDirty('field') to check what changed.
     *
     * Here we guard against reactivating a cancelled event
     * back to 'published' directly — it must go through 'draft' first.
     */
    public function updating(EventCMS $event): void
    {
        // Prevent a cancelled event being set directly back to published
        if ($event->getOriginal('status') === 'cancelled' && $event->status === 'published') {
            $event->status = 'draft'; // Force back to draft; organizer must explicitly re-publish
            Log::warning('Cancelled event cannot be directly re-published. Reverted to draft.', [
                'event_id' => $event->id,
            ]);
        }
    }

    /**
     * Fires AFTER an existing event is successfully updated.
     * Logs which fields changed for auditing.
     */
    public function updated(EventCMS $event): void
    {
        $changed = $event->getChanges();
        unset($changed['updated_at']);

        if (! empty($changed)) {
            Log::info('Event updated', [
                'event_id' => $event->id,
                'changes'  => $changed,
            ]);
        }
    }

    /**
     * Fires BEFORE an event is deleted.
     * Cancels all pending registrations so attendees aren't left in limbo.
     * (Confirmed attendees will be notified via EventControllerCMS::update)
     */
    public function deleting(EventCMS $event): void
    {
        $event->registrations()
            ->where('status', 'pending')
            ->update(['status' => 'cancelled']);

        Log::info('Event deleted — pending registrations cancelled', [
            'event_id' => $event->id,
            'title'    => $event->title,
        ]);
    }

}