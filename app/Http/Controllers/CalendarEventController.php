<?php

namespace App\Http\Controllers;

use App\Actions\Events\CreateEventAction;
use App\Http\Requests\EventCreateRequestData;
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
        private GetOrCreateUserService $getOrCreateUserService,
    )
    {
    }

    public function index()
    {
        $user = User::find(auth('api')->id());
        return EventResourceData::collect($user->attendedEvents()->paginate(10) );
    }

    public function show(CalendarEvent $event): EventResourceData
    {
        return EventResourceData::fromModel($event);
    }

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
