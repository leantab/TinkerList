<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeatherInfo extends Model
{
    use HasFactory;

    protected $table = 'weather_info';

    protected $guarded = [];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function event()
    {
        return $this->belongsTo(CalendarEvent::class);
    }
}
