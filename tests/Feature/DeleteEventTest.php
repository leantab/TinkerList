<?php

namespace Tests\Feature;

use App\Jobs\GetWeatherInfoForEventJob;
use App\Jobs\SendEmailInvitationJob;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class DeleteEventTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function test_create_and_delete_event(): void
    {
        Queue::fake()->except([
            GetWeatherInfoForEventJob::class,
        ]);

        $user = User::factory()->create();

        $location = 'Paris';
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
        
        $eventId = $response->json('id');

        $response = $this->actingAs($user, 'api')
            ->withSession(['banned' => false])
            ->withHeaders(['Accept', 'application/json'])
            ->delete('/api/events/'.$eventId);
        
        // $response->dump();
        $response->assertStatus(200);

        $this->assertDatabaseMissing('calendar_events', [
            'name' => 'Test Event',
        ]);
    }
}
