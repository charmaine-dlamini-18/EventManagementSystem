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
 * RegistrationDeclinedCMS
 *
 * Sent to an attendee when an organizer declines their registration.
 */
class RegistrationDeclinedCMS extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly RegistrationCMS $registration
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '❌ Registration Declined: ' . $this->registration->event->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.registrations.declined',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}