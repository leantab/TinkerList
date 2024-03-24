<?php

namespace App\Jobs;

use App\Models\CalendarEvent;
use App\Models\WeatherInfo;
use App\Services\External\WeatherApiService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GetWeatherInfoForEventJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public CalendarEvent $event
    )
    {
    }

    public function handle(): void
    {
        $location = $this->event->location;

        $service  = new WeatherApiService();
        if ($this->event->date_time > Carbon::now()->addDays(14)) {
            $response = $service->getFutureForecast($location->city, $this->event->date_time->format('Y-m-d'));
        } else {
            $response = $service->getForecastForEvent(
                $this->event->location->city, 
                $this->event->date_time->format('Y-m-d'), 
                $this->event->date_time->format('H')
            );
        }
        // dd($response);

        $temp = $response['forecast']['forecastday'][0]['day']['avgtemp_c'] . '°C (' . $response['forecast']['forecastday'][0]['day']['avgtemp_f'] . '°F)';
        $desc = $response['forecast']['forecastday'][0]['day']['condition']['text'];
        $weather = $response['forecast']['forecastday'][0]['day']['condition']['icon'];
        $precipitation = $response['forecast']['forecastday'][0]['day']['daily_chance_of_rain'] . '%';

        $weatherInfo = WeatherInfo::create([
            'event_id' => $this->event->id,
            'location_id' => $location->id,
            'temperature' => $temp,
            'description' => $desc,
            'weather' => $weather,
            'precipitation_probability' => $precipitation,
            'raw_data' => json_encode($response),
        ]);
    }
}
