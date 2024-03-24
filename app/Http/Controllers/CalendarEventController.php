<?php

namespace App\Http\Controllers;

use App\Actions\Events\CreateEventAction;
use App\Actions\Events\DeleteEventAction;
use App\Actions\Events\UpdateEventAction;
use App\Http\Requests\EventCreateRequestData;
use App\Http\Requests\EventUpdateRequestData;
use App\Models\CalendarEvent;
use App\Models\User;
use App\Resources\EventResourceData;
use App\Services\GetOrCreateUserService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CalendarEventController extends Controller
{
    public function __construct(
        private CreateEventAction $creatEventAction,
        private UpdateEventAction $updateEventAction,
        private DeleteEventAction $deleteEventAction,
        private GetOrCreateUserService $getOrCreateUserService,
    )
    {
    }

    public function index()
    {
        $user = User::find(auth('api')->id());
        return EventResourceData::collect(
            $user->attendedEvents()->orderBy('date_time')->paginate(10) 
        );
    }

    public function show(int $eventId)
    {
        $event = CalendarEvent::findOrFail($eventId);
        return response()->json(EventResourceData::fromModel($event));
    }
    // {
    //     return response()->json(EventResourceData::fromModel($event));
    //     return EventResourceData::fromModel($event);
    // }

    public function store(EventCreateRequestData $data): JsonResponse
    {
        try {
            $event = $this->creatEventAction->__invoke($data);

            return response()->json(EventResourceData::fromModel($event), 201);
        } catch (Exception $e) {
            Log::error('Failed to create event', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    public function update(EventUpdateRequestData $data, int $eventId)
    {
        try {
            $event = CalendarEvent::findOrFail($eventId);
            $updatedEvent = $this->updateEventAction->__invoke($data, $event);

            return response()->json(EventResourceData::fromModel($updatedEvent));
        } catch (Exception $e) {
            Log::error('Failed to update event', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy(int $eventId)
    {
        try {
            $event = CalendarEvent::findOrFail($eventId);
            $this->deleteEventAction->__invoke($event);

            return response()->json(['message' => 'Event deleted successfully']);
        } catch (Exception $e) {
            Log::error('Failed to delete event', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
