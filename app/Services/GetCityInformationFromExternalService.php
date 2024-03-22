<?php

namespace App\Services;

use App\Services\External\WeatherApiService;
use Exception;
use Illuminate\Support\Facades\Log;

class GetCityInformationFromExternalService
{
    public function __construct(
        private WeatherApiService $weatherApiService
    )
    {
    }

    public function getCityInformation(string $city): ?array
    {
        try {
            $cityInfo = $this->weatherApiService->search($city);

            if (is_array($cityInfo) && count($cityInfo) > 0) {
                return [
                    'name' => $city,
                    'region' => $cityInfo[0]['region'],
                    'country' => $cityInfo[0]['country'],
                    'latitude' => $cityInfo[0]['lat'],
                    'longitude' => $cityInfo[0]['lon'],
                    'external_id' => $cityInfo[0]['id']
                ];
            }
            
        } catch (Exception $e) {
            Log::error('Error getting location info from weatherApi service: ' . $e->getMessage());
            return null;
        }
        
        return null;
    }
}