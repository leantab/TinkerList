<?php

namespace App\Services;

use App\Actions\Locations\CreateLocationAction;
use App\Http\Requests\LocationCreateRequestData;
use App\Models\Location;
use Exception;

class GetOrCreateLocationService
{
    public function __construct(
        protected GetCityInformationFromExternalService $getCityInformationFromExternalService,
        protected CreateLocationAction $createLocationAction,
    )
    {        
    }

    public function __invoke(string $locationName)
    {
        $location = Location::whereAny(['name', 'city'], 'LIKE',  $locationName);

        if (!$location) {
            $location = $this->createLocation($locationName);
        }
        
        return $location;
    }

    protected function createLocation(string $locationName)
    {
        $cityInfo = $this->getCityInformationFromExternalService->getCityInformation($locationName);

        if (is_array($cityInfo)){
            $location = $this->createLocationAction->__invoke(LocationCreateRequestData::from([
                'name' => $locationName,
                'city' => $locationName,
                'region' => $cityInfo['region'],
                'country' => $cityInfo['country'],
                'latitude' => $cityInfo['lat'],
                'longitude' => $cityInfo['lon'],
            ]));
            
            return $location;
        } 

        throw new Exception('Error creating location. Location not found');
    }
}