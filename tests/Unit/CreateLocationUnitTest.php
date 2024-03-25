<?php

namespace Tests\Unit;

use App\Actions\Locations\CreateLocationAction;
use App\Http\Requests\LocationCreateRequestData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateLocationUnitTest extends TestCase
{
    use RefreshDatabase;

    public function create_location_test(): void
    {
        $data = LocationCreateRequestData::from([
            'name' => 'Austin HQ',
            'city' => 'Austin',
            'country' => 'United States',
        ]);

        $action = $this->createMock(CreateLocationAction::class);
        $action->__invoke($data);

        $this->assertDatabaseHas('locations', [
            'name' => 'Austin HQ',
            'city' => 'Austin',
        ]);
    }
}
