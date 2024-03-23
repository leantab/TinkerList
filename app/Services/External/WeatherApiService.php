<?php

namespace App\Services\External;

use Illuminate\Support\Facades\Http;

class WeatherApiService
{
    protected $baseUrl;
    protected $apiKey;

    public function __contruct()
    {
        $this->baseUrl = config('app.weather_api_base_uri');
        $this->apiKey = config('app.weather_api_api_key');
    }

    /**
     * Get the current weather for a location
     *
     * @param string $location
     * @return array
     */
    public function getCurrentWeather($location): array
    {
        $this->loadConfig();

        $endpoint = $this->baseUrl . '/current.json';
        $query = [
            'key' => $this->apiKey,
            'q' => $location,
        ];

        return $this->makeRequest($endpoint, $query);
    }

    /**
     * Get the forecast for a location
     *
     * @param string $location
     * @param int $days
     * @return array
     */
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
    
    /**
     * Get the forecast for a specific event
     *
     * @param string $location
     * @param string $date
     * @param int $hour
     * @return array
     */
    public function getForecastForEvent(string $location, string $date, int $hour): array
    {
        $this->loadConfig();

        $endpoint = $this->baseUrl . '/forecast.json';
        $query = [
            'key' => $this->apiKey,
            'q' => $location,
            'days' => 1,
            'dt' => $date, // Format yyyy-MM-dd ('2024-03-20')
            'hour' => $hour, // on 24hs without leading 0 (0-23)
        ];

        return $this->makeRequest($endpoint, $query);
    }

    /**
     * Search for a location by text
     *
     * @param string $location
     * @return array
     */
    public function search(string $location): array
    {
        $this->loadConfig();
        
        $endpoint = $this->baseUrl . '/search.json';
        $query = [
            'key' => $this->apiKey,
            'q' => $location,
        ];

        return $this->makeRequest($endpoint, $query);
    }
    
    /**
     * Get the future forecast for a location on a specific date
     *
     * @param string $location
     * @param string $date
     * @return array
     */
    public function getFutureForecast(string $location, string $date): array
    {
        $this->loadConfig();
        
        $endpoint = $this->baseUrl . '/future.json';
        $query = [
            'key' => $this->apiKey,
            'q' => $location,
            'dt' => $date, // Format yyyy-MM-dd ('2024-03-20')
            'lang' => 'en',
        ];

        return $this->makeRequest($endpoint, $query);
    }

    private function loadConfig(): void
    {
        // done here in case config is not loaded on service bootstrap
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