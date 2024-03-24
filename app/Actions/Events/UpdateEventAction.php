<?php

namespace App\Actions\Events;

use App\Http\Requests\EventUpdateRequestData;
use App\Models\CalendarEvent;
use App\Services\GetOrCreateLocationService;
use Carbon\Carbon;

class UpdateEventAction
{
    public function __construct(
        protected GetOrCreateLocationService $getOrCreateLocationService,
    )
    {
    }

    public function __invoke(EventUpdateRequestData $data, CalendarEvent $event): CalendarEvent
    {
        $location = $this->getOrCreateLocationService->__invoke($data->locationName);

        $event->update([
            'name' => $data->name,
            'location_id' => $location->id,
            'date_time' => Carbon::parse($data->dateTime),
        ]);

        return $event;
    }
}
