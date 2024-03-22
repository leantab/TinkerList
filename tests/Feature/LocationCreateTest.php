<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocationCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_post_request_to_create_location(): void
    {
        $response = $this->post('/api/locations', [
            'name' => 'Austin HQ',
            'city' => 'Austin',
            'country' => 'United States',
        ]);

        $response->assertStatus(201);
    }
}
