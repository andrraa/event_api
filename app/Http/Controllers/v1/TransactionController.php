<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Models\Ticket;
use App\Models\Transaction;
use Exception;
use Illuminate\Http\JsonResponse;

class TransactionController extends Controller
{
    public function create(TransactionRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        try {
            $ticket = Ticket::query()
                ->where('id', $validatedData['ticket_id'])
                ->first();

            $totalPrice = $ticket->price * $validatedData['quantity'];

            $validatedData['total_price'] = $totalPrice;

            $transaction = Transaction::query()->create($validatedData);

            if ($transaction) {
                return response()->json([
                    'code' => 201,
                    'message' => 'SUCCESS_CREATE_TRANSACTION',
                    'data' => new TransactionResource($transaction)
                ], 201);
            }

            return response()->json([
                'code' => 400,
                'message' => 'FAILED_CREATE_TRANSACTION',
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
            $transaction = Transaction::query()
                ->with(['masterEvent', 'ticket'])
                ->where('id', $id)
                ->where('is_active', 1)
                ->first();

            if ($transaction) {
                return response()->json([
                    'code' => 200,
                    'message' => 'SUCCESS_GET_TRANSACTION',
                    'data' => new TransactionResource($transaction)
                ]);
            }

            return response()->json([
                'code' => 404,
                'message' => 'FAILED_GET_TRANSACTION',
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(int $id, TransactionRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        try {
            $transaction = Transaction::query()
                ->where('id', $id)
                ->where('is_active', 1)
                ->first();

            if ($transaction) {
                $ticket = Ticket::query()
                    ->where('id', $transaction->ticket_id)
                    ->first();

                $totalPrice = $ticket->price * $validatedData['quantity'];

                $validatedData['total_price'] = $totalPrice;

                $transaction->update($validatedData);

                return response()->json([
                    'code' => 200,
                    'message' => 'SUCCESS_UPDATE_TRANSACTION',
                    'data' => new TransactionResource($transaction)
                ]);
            }

            return response()->json([
                'code' => 400,
                'message' => 'FAILED_UPDATE_TRANSACTION',
            ], 400);
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
            $transaction = Transaction::query()
                ->where('id', $id)
                ->where('is_active', 1)
                ->first();

            if ($transaction) {
                $transaction->is_active = 0;
                $transaction->save();

                return response()->json()->setStatusCode(204);
            }

            return response()->json([
                'code' => 404,
                'message' => 'TRANSACTION_NOT_FOUND',
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
            $transactions = Transaction::query()
                ->with(['masterEvent', 'ticket'])
                ->where('is_active', 1)
                ->get();

            if ($transactions->isEmpty()) {
                return response()->json([
                    'code' => 404,
                    'message' => 'TRANSACTION_NOT_FOUND',
                ], 404);
            }

            $transactionCollection = TransactionResource::collection($transactions);

            return response()->json([
                'code' => 200,
                'message' => 'SUCCESS_GET_TRANSACTIONS',
                'data' => $transactionCollection
            ]);
        } catch (Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
