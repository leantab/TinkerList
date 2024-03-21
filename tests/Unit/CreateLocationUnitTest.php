<?php

namespace Tests\Unit;

use App\Actions\Locations\CreateLocationAction;
use App\Http\Requests\LocationCreateRequestData;
use App\Services\External\WeatherApiService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateLocationUnitTest extends TestCase
{
    use RefreshDatabase;

    public function __construct(
        private CreateLocationAction $createLocationAction
    )
    {
    }

    public function create_location_test(): void
    {
        $data = LocationCreateRequestData::from([
            'name' => 'Austin HQ',
            'city' => 'Austin',
            'country' => 'United States',
        ]);

        $weatherApiService = $this->createMock(WeatherApiService::class);
        $action = new CreateLocationAction($weatherApiService);
        $action->__invoke($data);

        $this->assertDatabaseHas('locations', [
            'name' => 'Austin HQ',
            'city' => 'Austin',
            'country' => 'United States',
        ]);
    }
}
