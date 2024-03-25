<?php

namespace Database\Seeders;

use App\Models\CalendarEvent;
use App\Models\Location;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $locations = Location::factory(20)->create();

        $users = User::factory(10)->create();

        $events = CalendarEvent::factory(30)->create();

        $events->each(function ($event) use ($users) {
            $event->attendees()->attach(
                $users->random(rand(1, 5))->pluck('id')->toArray()
            );
        });

        $events->each(function ($event) {
            $event->weatherInfo()->create([
                'temperature' => rand(-20, 40),
                'description' => ['sunny', 'rainy', 'cloudy', 'snowy'][rand(0, 3)],
                'weather' => ['sunny', 'rainy', 'cloudy', 'snowy'][rand(0, 3)],
                'event_id' => $event->id,
                'location_id' => $event->location_id,
                'precipitation_probability' => rand(0, 100),
            ]);
        });
    }
}
