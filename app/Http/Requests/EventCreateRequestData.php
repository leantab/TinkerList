<?php

namespace App\Http\Requests;

use DateTime;
use Illuminate\Http\Request;
use Spatie\LaravelData\Data;

class EventCreateRequestData extends Data
{
    public function __construct(
        public string $name,
        public string $locationName,
        public DateTime $dateTime,
        public array $attendees,
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