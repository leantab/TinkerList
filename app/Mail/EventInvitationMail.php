<?php

namespace App\Mail;

use App\Models\CalendarEvent;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EventInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public CalendarEvent $event,
    )
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            to: new Address($this->user->email, $this->user->name),
            subject: 'Event Invitation', 
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'email.event_invitation',
            with: [
                'user' => $this->user,
                'event' => $this->event,
                'url' => 'http://example.com/accept-event-invitation',
                'newUser' => ($this->user->email == $this->user->name),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
