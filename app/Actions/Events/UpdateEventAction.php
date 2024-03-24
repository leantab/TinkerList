<?php

namespace App\Actions\Events;

use App\Http\Requests\EventUpdateRequestData;
use App\Jobs\SendEmailInvitationJob;
use App\Models\CalendarEvent;
use App\Services\GetOrCreateLocationService;
use App\Services\GetOrCreateUserService;
use Carbon\Carbon;

class UpdateEventAction
{
    public function __construct(
        protected GetOrCreateLocationService $getOrCreateLocationService,
        protected GetOrCreateUserService $getOrCreateUserService,
    )
    {
    }

    public function __invoke(EventUpdateRequestData $data, CalendarEvent $event): CalendarEvent
    {
        if ($data->name === null) {
            $data->name = $event->name;
        }

        if (
            $data->locationName !== null
            && $data->locationName != $event->location->city 
            && $data->locationName !== $event->location->name
        ) {
            $location = $this->getOrCreateLocationService->__invoke($data->locationName);
        } else {
            $location = $event->location;
        }

        if ($data->dateTime === null) {
            $data->dateTime = $event->date_time;
        }

        $event->update([
            'name' => $data->name,
            'location_id' => $location->id,
            'date_time' => Carbon::parse($data->dateTime),
        ]);

        $this->updateAttendees($data, $event);

        return $event;
    }

    protected function updateAttendees(EventUpdateRequestData $data, CalendarEvent $event): void
    {
        if ($data->attendees === null) {
            return;
        }

        $userEmail = auth('api')->user()->email;
        if (!in_array($userEmail, $data->attendees)) {
            $data->attendees[] = $userEmail;
        }

        $current = $event->attendees->pluck('email')->toArray();
        $toAdd = array_diff($data->attendees, $current);
        $toRemove = array_diff($current, $data->attendees);

        foreach ($toAdd as $attendeeEmail) {
            $attendee = $this->getOrCreateUserService->__invoke($attendeeEmail);
            $event->attendees()->attach($attendee);

            SendEmailInvitationJob::dispatch($event);
        }

        foreach ($toRemove as $attendeeEmail) {
            $attendee = $this->getOrCreateUserService->__invoke($attendeeEmail);
            $event->attendees()->detach($attendee);
        }

    }
}
