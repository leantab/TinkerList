<?php

namespace App\Resources;

use App\Models\Location;
use Spatie\LaravelData\Data;

class LocationResourceData extends Data
{
    public function __construct(
        public string $name,
        public string $city,
        public string $country,
        public string $latitude,
        public string $longitude,
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
        );
    }
}