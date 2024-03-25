<?php

namespace App\Resources;

use App\Models\WeatherInfo;
use Spatie\LaravelData\Data;

class WeatherResourceData extends Data
{
    public function __construct(
        public ?string $temperature,
        public ?string $description,
        public ?string $weather,
        public ?string $precipitationProbability,
    )
    {
    }

    public static function fromModel(WeatherInfo $weatherInfo): self
    {
        return new self(
            $weatherInfo->temperature ?? null,
            $weatherInfo->description ?? null,
            $weatherInfo->weather ?? null,
            $weatherInfo->precipitation_probability ?? null,
        );
    }
}