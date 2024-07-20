<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventCreateRequest;
use App\Http\Requests\EventUpdateRequest;
use App\Http\Resources\EventResource;
use App\Http\Resources\MasterEventResource;
use App\Models\Event;
use App\Models\MasterEvent;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function create(EventCreateRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        try {
            $image = $request->file('image');
            $imageName = $image->hashName();

            $validatedData['image'] = $imageName;

            $event = Event::query()->create($validatedData);

            if ($event) {
                $image->storeAs('public/events', $imageName, 'public');
                $event->load(['province', 'category', 'masterEvent']);

                return response()->json([
                    'code' => 201,
                    'message' => 'EVENT_CREATED',
                    'data' => new EventResource($event),
                ], 201);
            }

            return response()->json([
                'code' => 400,
                'message' => 'FAILED_CREATE_EVENT',
            ], 400);
        } catch (Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function view(int $id): JsonResponse
    {
        try {
            $event = Event::query()
                ->with(['province', 'category', 'masterEvent'])
                ->where('id', $id)
                ->where('is_active', 1)
                ->first();

            if ($event) {
                return response()->json([
                    'code' => 200,
                    'message' => 'EVENT_VIEW',
                    'data' => new EventResource($event)
                ]);
            }

            return response()->json([
                'code' => 404,
                'message' => 'EVENT_NOT_FOUND'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(int $id, EventUpdateRequest $request): JsonResponse
    {
        $validateData = $request->validated();

        try {
            $event = Event::query()->where('id', $id)
                ->where('is_active', 1)
                ->first();

            if (!$event) {
                return response()->json([
                    'code' => 404,
                    'message' => 'EVENT_NOT_FOUND'
                ], 404);
            }

            if ($request->hasFile('image')) {
                $oldImagePath = 'public/events/' . $event->image;

                if (Storage::disk('public')->exists($oldImagePath)) {
                    Storage::disk('public')->delete($oldImagePath);
                };

                $image = $request->file('image');
                $imageName = $image->hashName();

                $image->storeAs('public/events', $imageName, 'public');

                $validateData['image'] = $imageName;
            }

            $result = $event->update($validateData);

            if ($result) {
                return response()->json([
                    'code' => 200,
                    'message' => 'EVENT_UPDATED',
                    'data' => new EventResource($event)
                ]);
            }

            return response()->json([
                'code' => 400,
                'message' => 'FAILED_UPDATE_EVENT',
            ], 400);
        } catch (Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function delete(int $id): JsonResponse
    {
        try {
            $event = Event::query()
                ->where('id', $id)
                ->where('is_active', 1)
                ->first();

            if ($event) {
                $event->is_active = 0;
                $event->save();

                return response()->json()->setStatusCode(204);
            }

            return response()->json([
                'code' => 404,
                'message' => 'EVENT_NOT_FOUND'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function index(): JsonResponse
    {
        try {
            $events = Event::query()
                ->with(['province', 'category', 'masterEvent'])
                ->where('is_active', 1)
                ->get();

            if ($events->isEmpty()) {
                return response()->json([
                    'code' => 404,
                    'message' => 'EVENT_NOT_FOUND'
                ], 404);
            }

            $eventsCollection = EventResource::collection($events);

            return response()->json([
                'code' => 200,
                'message' => 'EVENTS',
                'data' => $eventsCollection
            ]);
        } catch (Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getEventList(): JsonResponse
    {
        try {
            $events = MasterEvent::query()
                ->with(['events' => function ($query) {
                    $query->where('is_active', 1);
                }])
                ->get();

            if ($events->isEmpty()) {
                return response()->json([
                    'code' => 404,
                    'message' => 'EVENT_NOT_FOUND'
                ], 404);
            }

            $eventsCollection = MasterEventResource::collection($events);

            return response()->json([
                'code' => 200,
                'message' => 'EVENTS',
                'data' => $eventsCollection
            ]);
        } catch (Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
