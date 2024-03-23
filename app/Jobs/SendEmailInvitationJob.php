<?php

namespace App\Jobs;

use App\Mail\EventInvitationMail;
use App\Models\CalendarEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmailInvitationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public CalendarEvent $event
    )
    {}

    public function handle(): void
    {
        $event = $this->event;
        $event->attendees()->each(function ($user) use ($event) {
           Mail::to($user->email)->send(new EventInvitationMail($user, $event));
        });
    }
}
