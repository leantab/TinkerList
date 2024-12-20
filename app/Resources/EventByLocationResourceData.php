<?php

namespace App\Resources;

use App\Models\CalendarEvent;
use Spatie\LaravelData\Data;

class EventByLocationResourceData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public string $locationName,
        public string $dateTime,
        public array $attendees,
        public ?WeatherResourceData $weather,
    )
    {
    }

    public static function fromModel(CalendarEvent $event): self
    {
        return new self(
            $event->id,
            $event->name,
            $event->location->name,
            $event->date_time->format('Y-m-d H:i:s'),
            $event->attendees->pluck('email')->toArray(),
            ($event->weatherInfo != null) ? WeatherResourceData::fromModel($event->weatherInfo) : null,
        );
    }
}