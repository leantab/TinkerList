<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocationCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_post_request_to_create_location(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'api')->post('/api/locations', [
            'name' => 'NYC HQ',
            'city' => 'New York',
            'country' => 'United States',
        ]);

        $response->assertStatus(201);
    }
}
