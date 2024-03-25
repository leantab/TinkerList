<?php

namespace Tests\Feature;

use App\Jobs\GetWeatherInfoForEventJob;
use App\Jobs\SendEmailInvitationJob;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class UpdateEventTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function test_event_updated(): void
    {
        Queue::fake()->except([
            GetWeatherInfoForEventJob::class,
        ]);

        $user = User::factory()->create();

        $location = 'Madrid';
        $email1 = $this->faker->safeEmail();
        $email2 = $this->faker->safeEmail();
        $email3 = $this->faker->safeEmail();
        
        // Create event
        $response = $this->actingAs($user, 'api')
            ->withSession(['banned' => false])
            ->withHeaders(['Accept', 'application/json'])
            ->post('/api/events', [
                'name' => 'Test Event',
                'date_time' => '2024-04-01 12:00:00',
                'location_name' => $location,
                'invitees' => [$email1, $email2],
            ]);

        // Check event was created
        $response->assertStatus(201);
        
        $eventId = $response->json('id');

        // Update event
        $response = $this->actingAs($user, 'api')
            ->withSession(['banned' => false])
            ->withHeaders(['Accept', 'application/json'])
            ->post('/api/events/edit/'.$eventId, [
                'name' => 'Updated Test Event',
                'location_name' => $location,
                'date_time' => '2024-04-04 14:00:00',
                'invitees' => [$email1, $email3],
            ]);
        
        // check event was updated
        $response->assertStatus(200);

        $this->assertDatabaseHas('calendar_events', [
            'name' => 'Updated Test Event',
        ]);

        $this->assertDatabaseHas('calendar_event_attendees', [
            'calendar_event_id' => $eventId,
            'user_id' => User::where('email', $email1)->first()->id,
        ]);

        $this->assertDatabaseHas('calendar_event_attendees', [
            'calendar_event_id' => $eventId,
            'user_id' => User::where('email', $email3)->first()->id,
        ]);
        
        $this->assertDatabaseMissing('calendar_event_attendees', [
            'calendar_event_id' => $eventId,
            'user_id' => User::where('email', $email2)->first()->id,
        ]);

        Queue::assertPushed(SendEmailInvitationJob::class);
        // Queue::assertPushed(GetWeatherInfoForEventJob::class);
    }
}
