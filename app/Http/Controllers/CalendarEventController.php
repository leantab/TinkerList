<?php

namespace App\Http\Controllers;

use App\Actions\Events\CreateEventAction;
use App\Actions\Events\DeleteEventAction;
use App\Actions\Events\UpdateEventAction;
use App\Http\Requests\EventCreateRequestData;
use App\Http\Requests\EventUpdateRequestData;
use App\Models\CalendarEvent;
use App\Models\Location;
use App\Models\User;
use App\Resources\EventResourceData;
use App\Resources\LocationWithEventsResourceData;
use App\Services\GetOrCreateUserService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    public function index(Request $request)
    {
        $user = User::find(auth('api')->id());
        return EventResourceData::collect(
            CalendarEvent::where(function($query) use ($request, $user) {
                    if ($request->has('date_from')) {
                        $query->where('date_time', '>=', Carbon::parse($request->date_from));
                    }
                    if ($request->has('date_to')) {
                        $query->where('date_time', '<=', Carbon::parse($request->date_to));
                    }
                    if ($request->has('location_id')) {
                        $query->where('location_id', $request->location_id);
                    }
                    $query->whereIn('id', $user->attendedEvents()->pluck('id')->toArray());
                })
            ->with(['location', 'attendees'])
            ->orderBy('date_time')->paginate(10) 
        );
    }

    public function byLocations()
    {
        $user = User::find(auth('api')->id());
        $events = $user->attendedEvents;
        $locations = Location::whereHas('events' , function($query) use ($events) {
            $query->whereIn('id', $events->pluck('id')->toArray());
        })->with('events', 'events.attendees')->get();

        return LocationWithEventsResourceData::collect($locations);
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
