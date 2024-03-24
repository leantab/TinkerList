<?php

namespace Tests\Unit;

// use App\Actions\Events\CreateEventAction;
// use App\Http\Requests\EventCreateRequestData;
use App\Jobs\GetWeatherInfoForEventJob;
use App\Models\CalendarEvent;
use App\Models\Location;
use App\Models\User;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class CreateEventTest extends TestCase
{
    public function test_weather_retreived_for_new_event(): void
    {
        Queue::fake()->except([
            GetWeatherInfoForEventJob::class,
        ]);

        $user = User::factory()->create();

        $location = Location::factory()->create([
            'city' => 'Paris',
            'country' => 'France',
        ]);

        $event = CalendarEvent::create([
            'name' => 'Laravel Event',
            'location_id' => $location->id,
            'date_time' => '2024-04-01 12:00:00',
            'creator_id' => $user->id
        ]);

        $weatherJob = new GetWeatherInfoForEventJob($event);
        $weatherJob->handle();

        $this->assertDatabaseHas('calendar_events', [
            'name' => 'Laravel Event',
        ]);

        $this->assertDatabaseHas('weather_info', [
            'event_id' => $event->id,
        ]);
    }
}
