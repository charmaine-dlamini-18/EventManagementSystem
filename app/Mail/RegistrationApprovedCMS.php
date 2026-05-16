<?php

namespace App\Mail;

use App\Models\RegistrationCMS;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * RegistrationApprovedCMS
 *
 * Sent to an attendee when an organizer approves their registration.
 * Implements ShouldQueue so it's dispatched to the queue (non-blocking).
 */
class RegistrationApprovedCMS extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     * The Registration model (with event + user loaded) is injected.
     */
    public function __construct(
        public readonly RegistrationCMS $registration
    ) {}

    /**
     * Get the message envelope (subject, from address).
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '✅ Registration Approved: ' . $this->registration->event->title,
        );
    }

    /**
     * Get the message content — points to the Blade email view.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.registrations.approved',
        );
    }

    /**
     * Attachments (none needed here).
     */
    public function attachments(): array
    {
        return [];
    }
}