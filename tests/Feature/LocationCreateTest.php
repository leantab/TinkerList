<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocationCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->post('/locations', [
            'name' => 'Austin HQ',
            'city' => 'Austin',
            'country' => 'United States',
        ]);

        $response->assertStatus(200);
    }
}
