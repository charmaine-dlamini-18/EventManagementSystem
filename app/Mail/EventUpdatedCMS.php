<?php

namespace App\Mail;

use App\Models\EventCMS;
use App\Models\UserCMS;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * EventUpdatedCMS
 *
 * Sent to all confirmed registrants when an organizer updates an event's
 * key details (title, date, location, status).
 *
 * Usage in controller:
 *   $event->registrations()->where('status', 'confirmed')->with('user')->get()
 *       ->each(fn($reg) => Mail::to($reg->user)->queue(new EventUpdatedCMS($event, $reg->user)));
 */
class EventUpdatedCMS extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly EventCMS $event,
        public readonly UserCMS  $attendee,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '📅 Event Updated: ' . $this->event->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.events.updated',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}