<?php

namespace App\Resources;

use App\Models\CalendarEvent;
use Spatie\LaravelData\Data;

class EventResourceData extends Data
{
    public function __construct(
        public string $name,
        public string $locationName,
        public string $dateTime,
        public array $attendees,
        public LocationResourceData $location,
        public WeatherResourceData $weather,
    )
    {
    }

    public static function fromModel(CalendarEvent $event): self
    {
        return new self(
            $event->name,
            $event->location->name,
            $event->date_time->toIso8601String(),
            $event->attendees->pluck('email')->toArray(),
            LocationResourceData::fromModel($event->location),
            WeatherResourceData::fromModel($event->weatherInfo),
        );
    }
}