<?php

namespace App\Http\Controllers;

use App\Actions\Locations\CreateLocationAction;
use App\Http\Requests\LocationCreateRequestData;
use App\Models\Location;
use App\Services\External\WeatherApiService;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function __construct(
        private CreateLocationAction $createLocationAction
    )
    {        
    }

    public function index()
    {
        return Location::all();
    }

    public function show($id)
    {
        return Location::findOrFail($id);
    }

    public function store(LocationCreateRequestData $data)
    {
        return $this->createLocationAction->__invoke($data);
    }

    public function update(Request $request, Location $location)
    {
        //
    }

    public function destroy(Location $location)
    {
        //
    }
}
