<?php

namespace App\Http\Controllers;

use App\Http\Requests\LocationCreateRequestData;
use App\Models\Location;
use App\Services\External\WeatherApiService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function __construct(
        protected WeatherApiService $weatherApiService
    )
    {        
    }

    /**
     * Display a listing of the resource.
     * @return Collection<int, Location> 
    /**
     * Class LocationController
     * @package App\Http\Controllers
        * Get all locations.
        *
        * @return Location[]
        *
        * @OA\Get(
        *     path="/api/locations",
        *     summary="Get all locations",
        *     tags={"Locations"},
        *     @OA\Response(response=200, description="Successful operation", @OA\JsonContent(ref="#/components/schemas/Location")),
        *     @OA\Response(response=401, description="Unauthorized")
        * )
        */
    public function index()
    {
        return Location::all();
    }

    /**
     * Get a specific location by ID.
     *
     * @param int $id
     * @return Location
     *
     * @OA\Get(
     *     path="/api/locations/{id}",
     *     summary="Get a specific location",
     *     tags={"Locations"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Location ID",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(response=200, description="Successful operation", @OA\JsonContent(ref="#/components/schemas/Location")),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function show($id)
    {
        return Location::findOrFail($id);
    }

    /**
     * @OA\Post(
     *      path="/api/locations",
     *      summary="Create a new location",
     *      tags={"Locations"},
     * @OA\RequestBody(
     *      required=true,
     *      @OA\JsonContent(
     *          required={"name", "city", "country"},
     *          @OA\Property(property="name", type="string", example="New York"),
     *          @OA\Property(property="latitude", type="number", format="float", example=40.7128),
     *          @OA\Property(property="longitude", type="number", format="float", example=-74.0060)
     *      )
     * ),
     * @OA\Response(
     *      response=201,
     *      description="Location created successfully",
     *      @OA\JsonContent(ref="#/components/schemas/Location")
     * ),
     * 
     */
    public function store(LocationCreateRequestData $data)
    {
        return Location::create($data->toArray());
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param Location $location
     * @return Location
     * 
     * @OA\Put(
     *      path="/api/locations/{id}",
     *      summary="Update a location",
     *      tags={"Locations"},
     * @OA\Parameter(
     *      name="id",
     *      in="path",
     *      description="Location ID",
     *      required=true,
     * @OA\Schema(
     *     type="integer",
     *    format="int64"
     * )
     * )
     */
    public function update(Request $request, Location $location)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Location $location)
    {
        //
    }
}
