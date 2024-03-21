<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Spatie\LaravelData\Data;

class LocationCreateRequestData extends Data
{
    public function __construct(
        public string $name,
        public string $city,
        public string $country,
        public ?float $latitude = null,
        public ?float $longitude = null,
        public ?string $region = null,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            name: $request->input('name'),
            city: $request->input('city'),
            country: $request->input('country'),
        );
    }
}