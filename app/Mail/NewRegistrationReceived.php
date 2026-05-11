<?php

namespace App\Mail;

use App\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * NewRegistrationReceived
 *
 * Sent to the event organizer when a new attendee registers for their event.
 * Lets the organizer know there's a pending registration awaiting their action.
 */
class NewRegistrationReceived extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Registration $registration
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🔔 New Registration for: ' . $this->registration->event->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.registrations.new-registration',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}