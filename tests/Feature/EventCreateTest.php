<?php

namespace Tests\Feature;

use App\Models\Location;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_post_request_to_create_event_existing_location(): void
    {
        $user = User::factory()->create();
        $location = Location::factory()->create();

        $response = $this->actingAs($user)->post('/api/events', [
            'name' => 'Meeting at Paris HQ',
            'Location_name' => $location->name,
            'date_time' => '2022-04-01 12:00:00',
            'attendees' => ['tabajleandro@gmail.com', 'pepe@example.com'],
        ]);

        $response->assertStatus(201);
    }
}
