<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('weather_info', function (Blueprint $table) {
            $table->id();
            $table->foreignId('location_id')->constrained();
            $table->foreignId('event_id')->constrained('calendar_events');
            $table->string('temperature');
            $table->string('description');
            $table->string('weather');
            $table->string('precipitation_probability');
            $table->json('raw_data');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('weather_info');
    }
};
