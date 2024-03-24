<?php

namespace App\Http\Controllers;

use App\Actions\Locations\CreateLocationAction;
use App\Http\Requests\LocationCreateRequestData;
use App\Models\Location;
use App\Resources\LocationResourceData;
use App\Services\External\WeatherApiService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

    public function store(LocationCreateRequestData $data): JsonResponse
    {
        try {
            $location = $this->createLocationAction->__invoke($data);

            return response()->json(LocationResourceData::fromModel($location), 201);
        } catch (Exception $e) {
            Log::error('Failed to create location', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, int $id)
    {
        $location = Location::findOrFail($id);
        $location->update($request->all());
    }
}
