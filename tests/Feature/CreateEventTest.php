<?php

namespace Tests\Feature;

use App\Jobs\GetWeatherInfoForEventJob;
use App\Jobs\SendEmailInvitationJob;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class CreateEventTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function test_event_created(): void
    {
        Queue::fake()->except([
            GetWeatherInfoForEventJob::class,
        ]);

        $user = User::factory()->create();

        $location = 'Madrid';
        $email1 = $this->faker->safeEmail();
        $email2 = $this->faker->safeEmail();
        
        $response = $this->actingAs($user, 'api')
            ->withSession(['banned' => false])
            ->withHeaders(['Accept', 'application/json'])
            ->post('/api/events', [
                'name' => 'Test Event',
                'date_time' => '2024-04-01 12:00:00',
                'location_name' => $location,
                'invitees' => [$email1, $email2],
            ]);

        $response->assertStatus(201);
        
        $this->assertDatabaseHas('calendar_events', [
            'name' => 'Test Event',
        ]);
        
        $this->assertDatabaseHas('locations', [
            'city' => $location,
        ]);
        
        $this->assertDatabaseHas('users', [
            'email' => $email1,
        ]);
        $this->assertDatabaseHas('users', [
            'email' => $email2,
        ]);

        Queue::assertPushed(SendEmailInvitationJob::class);
        // Queue::assertPushed(GetWeatherInfoForEventJob::class);
    }
}
