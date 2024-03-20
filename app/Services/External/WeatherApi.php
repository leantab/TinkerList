<?php

namespace App\Services\External;

use Illuminate\Support\Facades\Http;

class WeatherApi
{
    protected $baseUrl;
    protected $apiKey;

    public function __contruct()
    {
        $this->baseUrl = config('app.weather_api_base_uri');
        // $this->baseUrl = 'https://api.weatherapi.com/v1';
        $this->apiKey = config('app.weather_api_api_key');
        // $this->apiKey = 'API_KEY';
    }

    public function getCurrentWeather($location): array
    {
        $this->loadConfig();

        $endpoint = $this->baseUrl . '/search.json';
        $query = [
            'key' => $this->apiKey,
            'q' => $location,
        ];

        return $this->makeRequest($endpoint, $query);
    }

    public function getForecast($location, $days = 3): array
    {
        $this->loadConfig();

        $endpoint = $this->baseUrl . '/forecast.json';
        $query = [
            'key' => $this->apiKey,
            'q' => $location,
            'days' => $days,
        ];

        return $this->makeRequest($endpoint, $query);
    }
    
    public function getForecastForEvent(string $location, string $date, int $hour): array
    {
        $this->loadConfig();

        $endpoint = $this->baseUrl . '/forecast.json';
        $query = [
            'key' => $this->apiKey,
            'q' => $location,
            'dt' => $date, // Format yyyy-MM-dd
            'hour' => $hour, // on 24hs without leading 0
        ];

        return $this->makeRequest($endpoint, $query);
    }

    public function search(string $location): array
    {
        $this->loadConfig();
        
        $endpoint = $this->baseUrl . '/search.json';
        $query = [
            'key' => $this->apiKey,
            'q' => $location,
        ];

        return $this->makeRequest($endpoint, $query);
        
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
    
    public function future(string $location, string $date): array
    {
        $this->loadConfig();
        
        $endpoint = $this->baseUrl . '/future.json';
        $query = [
            'key' => $this->apiKey,
            'q' => $location,
            'dt' => $date,
            'lang' => 'en',
        ];

        return $this->makeRequest($endpoint, $query);
        
        /* Example response {
        "location": {
            "name": "Paris",
            "region": "Ile-de-France",
            "country": "France",
            "lat": 48.87,
            "lon": 2.33,
            "tz_id": "Europe/Paris",
            "localtime_epoch": 1710939040,
            "localtime": "2024-03-20 13:50"
        },
        "forecast": {
            "forecastday": [
            {
                "date": "2024-04-15",
                "date_epoch": 1713139200,
                "day": {
                "maxtemp_c": 15.7,
                "maxtemp_f": 60.2,
                "mintemp_c": 6.3,
                "mintemp_f": 43.3,
                "avgtemp_c": 10.9,
                "avgtemp_f": 51.5,
                "maxwind_mph": 11,
                "maxwind_kph": 17.7,
                "totalprecip_mm": 1.17,
                "totalprecip_in": 0.05,
                "avgvis_km": 9.6,
                "avgvis_miles": 5,
                "avghumidity": 72,
                "condition": {
                    "text": "Heavy rain at times",
                    "icon": "//cdn.weatherapi.com/weather/64x64/day/305.png",
                    "code": 1192
                },
                "uv": 3
                },
                "astro": {
                "sunrise": "06:59 AM",
                "sunset": "08:43 PM",
                "moonrise": "11:29 AM",
                "moonset": "03:59 AM",
                "moon_phase": "First Quarter",
                "moon_illumination": 42
                },
                "hour": [
                {
                    "time_epoch": 1713132000,
                    "time": "2024-04-15 00:00",
                    "temp_c": 8.4,
                    "temp_f": 47,
                    "is_day": 0,
                    "condition": {
                    "text": "Partly cloudy",
                    "icon": "//cdn.weatherapi.com/weather/64x64/night/116.png",
                    "code": 1003
                    },
                    "wind_mph": 5.8,
                    "wind_kph": 9.4,
                    "wind_degree": 105,
                    "wind_dir": "ESE",
                    "pressure_mb": 1018,
                    "pressure_in": 30.07,
                    "precip_mm": 1.17,
                    "precip_in": 0.05,
                    "humidity": 84,
                    "cloud": 28,
                    "feelslike_c": 6.8,
                    "feelslike_f": 44.3,
                    "windchill_c": 6.8,
                    "windchill_f": 44.3,
                    "heatindex_c": 8.4,
                    "heatindex_f": 47,
                    "dewpoint_c": 5.8,
                    "dewpoint_f": 42.4,
                    "will_it_rain": 0,
                    "chance_of_rain": 0,
                    "will_it_snow": 0,
                    "chance_of_snow": 0,
                    "vis_km": 8.6,
                    "vis_miles": 5,
                    "gust_mph": 10.1,
                    "gust_kph": 16.2,
                    "uv": 1
                },
                ]*/
            }

    private function loadConfig(): void
    {
        $this->baseUrl = config('app.weather_api_base_uri');
        $this->apiKey = config('app.weather_api_api_key');
    }

    private function makeRequest(string $endpoint, array $payload): array
    {
        $response = Http::withOptions([
            'debug' => false,
            'verify' => false,
        ])
        ->get($endpoint, $payload);
        // dd($response->json());
        return $response->json();
    }
}