<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarEvent extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'calendar_events';

    protected $casts = [
        'date_time' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function attendees()
    {
        return $this->belongsToMany(User::class, 'calendar_event_attendees', 'calendar_event_id', 'user_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id', );
    } 

    public function weatherInfo()
    {
        return $this->hasOne(WeatherInfo::class, 'event_id');
    }
}
