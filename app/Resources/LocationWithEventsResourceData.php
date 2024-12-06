<?php

namespace App\Resources;

use App\Models\Location;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;

class LocationWithEventsResourceData extends Data
{
    public function __construct(
        public string $name,
        public string $city,
        public string $country,
        public string $latitude,
        public string $longitude,
        #[DataCollectionOf(EventByLocationResourceData::class)]
        public Collection $events,
    )
    {
    }

    public static function fromModel(Location $location): self
    {
        return new self(
            $location->name,
            $location->city,
            $location->country,
            $location->latitude,
            $location->longitude,
            EventByLocationResourceData::collect($location->events()->orderBy('date_time')->get())
        );
    }
}