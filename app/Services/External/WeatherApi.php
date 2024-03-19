<?php

namespace App\Services\External;

use Illuminate\Support\Facades\Http;

class WeatherApi
{
    protected $baseUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('weatherapi.base_url');
        $this->apiKey = config('weatherapi.api_key');
    }

    public function getCurrentWeather($location): array
    {
        $response = Http::get($this->baseUrl.'/current.json', [
            'query' => [
                'key' => $this->apiKey,
                'q' => $location,
            ],
        ]);

        return json_decode($response->json(), true);
    }

    public function getForecast($location, $days = 3): array
    {
        $response = Http::get($this->baseUrl.'/forecast.json', [
            'query' => [
                'key' => $this->apiKey,
                'q' => $location,
                'days' => $days,
            ],
        ]);

        return json_decode($response->getBody(), true);
    }
    
    public function getForecastForEvent(string $location, string $date, int $hour): array
    {
        $response = Http::get($this->baseUrl.'/forecast.json', [
            'query' => [
                'key' => $this->apiKey,
                'q' => $location,
                'dt' => $date, // Format yyyy-MM-dd
                'hour' => $hour, // on 24hs without leading 0
            ],
        ]);

        return json_decode($response->json(), true);
    }

    public function search(string $location): array
    {
        $response = Http::get($this->baseUrl.'/search.json', [
            'query' => [
                'key' => $this->apiKey,
                'q' => $location,
            ],
        ]);

        return json_decode($response->json(), true);
        /* Example response [
            {
                "id": 2796590,
                "name": "Holborn",
                "region": "Camden Greater London",
                "country": "United Kingdom",
                "lat": 51.52,
                "lon": -0.12,
                "url": "holborn-camden-greater-london-united-kingdom"
            }
        ]*/
    }
}