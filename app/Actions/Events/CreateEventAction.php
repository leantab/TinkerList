<?php

namespace App\Actions\Events;

use App\Http\Requests\EventCreateRequestData;
use App\Models\CalendarEvent;
use App\Services\GetOrCreateLocationService;
use App\Services\GetOrCreateUserService;
use Exception;

class CreateEventAction
{
    public function __construct(
        protected GetOrCreateLocationService $getOrCreateLocationService,
        protected GetOrCreateUserService $getOrCreateUserService,
    )
    {        
    }

    public function __invoke(EventCreateRequestData $data): CalendarEvent
    {
        $location = $this->getOrCreateLocationService->__invoke($data->locationName);

        $event = CalendarEvent::create([
            'name' => $data->name,
            'location_id' => $location->id,
            'date_time' => $data->dateTime,
            'creator_id' => auth('api')->id(),
        ]);

        $this->afterCreate($event, $data);

        return $event;
    }

    protected function afterCreate(CalendarEvent $event, EventCreateRequestData $data)
    {
       
        $user = auth('api')->user();
        $event->attendees()->attach($user);

        foreach ($data->attendees as $attendeeEmail) {
            // get or create user and attach to event
            $attendee = $this->getOrCreateUserService->__invoke($attendeeEmail);
            $event->attendees()->attach($attendee);
        }

        
    }
}