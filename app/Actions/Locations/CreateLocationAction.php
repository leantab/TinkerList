<?php

namespace App\Actions\Locations;

use App\Http\Requests\LocationCreateRequestData;
use App\Models\Location;
use App\Services\External\WeatherApiService;

class CreateLocationAction
{
    public function __construct(
        protected WeatherApiService $weatherApiService
    )
    {        
    }

    public function __invoke(LocationCreateRequestData $data)
    {
        $this->getInfoFromExternalService($data);

        $location = Location::create([
            'name' => $data->name,
            'city' => $data->city,
            'country' => $data->country,
            'latitude' => $data->latitude,
            'longitude' => $data->longitude,
            'region' => $data->region,
        ]);

        return $location;
    }

    protected function getInfoFromExternalService(LocationCreateRequestData $data)
    {
        $location = $data->city . ',' . $data->country;

        $apiInfo = $this->weatherApiService->search($location);

        if (is_array($apiInfo) && count($apiInfo) > 0){
            $data->latitude = $apiInfo[0]['latitude'];
            $data->longitude = $apiInfo[0]['longitude'];
            $data->region = $apiInfo[0]['longitude'];
        }
    }
}