<?php

namespace App\Actions\Locations;

use App\Http\Requests\LocationCreateRequestData;
use App\Models\Location;
use App\Services\External\WeatherApiService;
use App\Services\GetCityInformationFromExternalService;
use Exception;
use Illuminate\Support\Facades\Log;

class CreateLocationAction
{
    public function __construct(
        protected GetCityInformationFromExternalService $getCityInformationFromExternalService,
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
        if ($data->latitude != null && $data->longitude != null) {
            return;
        }

        $cityInfo = $this->getCityInformationFromExternalService->getCityInformation($data->city);

        if (is_array($cityInfo)){
            $data->latitude = $cityInfo['lat'];
            $data->longitude = $cityInfo['lon'];
            $data->region = $cityInfo['region'];
            $data->external_id = $cityInfo['id'];
        } else {
            return;
        }
    }
}