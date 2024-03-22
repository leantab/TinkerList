<?php

namespace App\Http\Controllers;

use App\Actions\Events\CreateEventAction;
use App\Http\Requests\EventCreateRequestData;
use App\Models\CalendarEvent;
use App\Services\GetOrCreateUserService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CalendarEventController extends Controller
{
    public function __construct(
        private CreateEventAction $creatEventAction,
        private GetOrCreateUserService $getOrCreateUserService,
    )
    {
    }

    public function index()
    {
        $user = auth('api')->user();
        return $user->attendedEvents;
    }

    public function store(EventCreateRequestData $data)
    {
        try {
            $event = $this->creatEventAction->__invoke($data);

            return response()->json($event, 201);
        } catch (Exception $e) {
            Log::error('Failed to create event', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    public function update(Request $request, CalendarEvent $calendarEvent)
    {
        //
    }

    public function destroy(CalendarEvent $event)
    {
        $event->attendees()->detach();

        $event->delete();

        return response()->json(['message' => 'Event deleted successfully']);
    }
}
