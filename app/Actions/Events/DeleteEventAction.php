<?php

namespace App\Actions\Events;

use App\Models\CalendarEvent;

class DeleteEventAction
{
    public function __invoke(CalendarEvent $event): void
    {
        $this->beforeDelete($event);

        $event->delete();
    }

    protected function beforeDelete(CalendarEvent $event): void
    {
        // detach all attendees
        $event->attendees()->detach();

        // delete weather info
        $event->weatherInfo()->delete();
    }
}