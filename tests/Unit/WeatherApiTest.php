<?php

namespace Tests\Unit;

use App\Services\External\WeatherApiService;
use Carbon\Carbon;
use Tests\TestCase;

class WeatherApiTest extends TestCase
{    
    public function test_search_api(): void
    {
        $weatherApi = new WeatherApiService();
        $response = $weatherApi->search('Austin');

        $this->assertIsArray($response);
        $data = $response[0];
        
        $this->assertIsArray($data);

        $this->assertArrayHasKey('lon', $data);
        $this->assertArrayHasKey('lat', $data);
    }
    
    public function test_forecast_api(): void
    {
        $weatherApi = new WeatherApiService();
        $response = $weatherApi->getForecast('London', 2);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('location', $response);
        $this->assertArrayHasKey('current', $response);
        $this->assertArrayHasKey('forecast', $response);
    }
    
    public function test_forecast_for_event_api(): void
    {
        $weatherApi = new WeatherApiService();
        $tomorrow = Carbon::now()->addDay()->day;
        $response = $weatherApi->getForecastForEvent('Paris', $tomorrow, 14);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('location', $response);
        $this->assertArrayHasKey('current', $response);
        $this->assertArrayHasKey('forecast', $response);
    }
    
    public function test_future_api(): void
    {
        $weatherApi = new WeatherApiService();
        $tomorrow = Carbon::now()->addDays(20)->format('Y-m-d');
        $response = $weatherApi->getForecastForEvent('Paris', $tomorrow, 14);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('location', $response);
        $this->assertArrayHasKey('forecast', $response);
    }
}
