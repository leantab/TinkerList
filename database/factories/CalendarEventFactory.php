<?php

namespace Database\Factories;

use App\Models\Location;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class CalendarEventFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'date_time' => Carbon::now()->addDays(random_int(1, 30)),
            'creator_id' => fake()->randomElement(User::pluck('id')->toArray()),
            'location_id' => fake()->randomElement(Location::pluck('id')->toArray()),
        ];
    }
}