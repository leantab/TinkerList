<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

class EventUpdateRequestData extends Data
{
    public function __construct(
        public ?string $name,
        #[MapInputName('location_name')]
        public ?string $locationName,
        #[MapInputName('date_time')]
        public ?string $dateTime,
        #[MapInputName('invitees')]
        public ?array $attendees,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            name: $request->input('name'),
            locationName: $request->input('location_name'),
            dateTime: $request->input('date_time'),
            attendees: $request->input('invitees'),
        );
    }
}