<?php

namespace Tests\Unit;

use App\Services\External\WeatherApi;
use Carbon\Carbon;
use Tests\TestCase;

class WeatherApiTest extends TestCase
{    
    public function test_search_api(): void
    {
        $weatherApi = new WeatherApi();
        $response = $weatherApi->search('New York');

        $this->assertIsArray($response);
        $this->assertArrayHasKey('lon', $response);
        $this->assertArrayHasKey('lat', $response);
    }
    
    public function test_get_forecast_api(): void
    {
        $weatherApi = new WeatherApi();
        $response = $weatherApi->getForecast('London', 2);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('lon', $response);
        $this->assertArrayHasKey('lat', $response);
    }
    
    public function test_get_forecast_for_event_api(): void
    {
        $weatherApi = new WeatherApi();
        $tomorrow = Carbon::now()->addDay()->day;
        $response = $weatherApi->getForecastForEvent('Paris', $tomorrow, 14);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('lon', $response);
        $this->assertArrayHasKey('lat', $response);
    }
}
