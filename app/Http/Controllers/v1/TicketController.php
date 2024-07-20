<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\TicketRequest;
use App\Http\Resources\MasterEventResource;
use App\Http\Resources\TicketResource;
use App\Models\MasterEvent;
use App\Models\Ticket;
use Exception;
use Illuminate\Http\JsonResponse;

class TicketController extends Controller
{
    public function create(TicketRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        try {
            $ticket = Ticket::query()->create($validatedData);

            if ($ticket) {
                return response()->json([
                    'code' => 201,
                    'message' => 'SUCCESS_CREATE_TICKET',
                    'data' => new TicketResource($ticket)
                ], 201);
            }

            return response()->json([
                'code' => 400,
                'message' => 'FAILED_CREATE_TICKET'
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
            $ticket = Ticket::query()
                ->with(['masterEvent'])
                ->where('id', $id)
                ->where('is_active', 1)
                ->first();

            if ($ticket) {
                return response()->json([
                    'code' => 200,
                    'message' => 'SUCCESS_VIEW_TICKET',
                    'data' => new TicketResource($ticket)
                ]);
            }

            return response()->json([
                'code' => 404,
                'message' => 'TICKET_NOT_FOUND'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(int $id, TicketRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        try {
            $ticket = Ticket::query()
                ->where('id', $id)
                ->where('is_active', 1)
                ->first();

            if (!$ticket) {
                return response()->json([
                    'code' => 404,
                    'message' => 'TICKET_NOT_FOUND'
                ], 404);
            }

            $ticket->update($validatedData);

            return response()->json([
                'code' => 200,
                'message' => 'SUCCESS_UPDATE_TICKET',
                'data' => new TicketResource($ticket)
            ]);
        } catch (Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function delete(int $id)
    {
        try {
            $ticket = Ticket::query()
                ->where('id', $id)
                ->where('is_active', 1)
                ->first();

            if ($ticket) {
                $ticket->is_active = 0;
                $ticket->save();

                return response()->json()->setStatusCode(204);
            }

            return response()->json([
                'code' => 404,
                'message' => 'TICKET_NOT_FOUND'
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
            $tickets = Ticket::query()
                ->with(['masterEvent'])
                ->where('is_active', 1)
                ->get();

            if ($tickets->isEmpty()) {
                return response()->json([
                    'code' => 404,
                    'message' => 'TICKET_NOT_FOUND'
                ], 404);
            }

            $ticketCollection = TicketResource::collection($tickets);

            return response()->json([
                'code' => 200,
                'message' => 'SUCCESS_LIST_TICKET',
                'data' => $ticketCollection
            ]);
        } catch (Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getTicketByEvent(int $id): JsonResponse
    {
        try {
            $ticket = MasterEvent::query()
                ->where('id', $id)
                ->with(['tickets' => function ($query) {
                    $query->where('is_active', 1);
                }])
                ->get();

            if ($ticket->isEmpty()) {
                return response()->json([
                    'code' => 404,
                    'message' => 'TICKET_NOT_FOUND'
                ], 404);
            }

            $ticketCollection = MasterEventResource::collection($ticket);

            return response()->json([
                'code' => 200,
                'message' => 'SUCCESS_LIST_TICKET_BY_EVENTS',
                'data' => $ticketCollection
            ]);
        } catch (Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
