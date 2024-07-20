<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserLoginRequest;
use App\Http\Resources\UserLoginResource;
use App\Http\Resources\UserProfileResource;
use App\Models\User;
use App\Models\UserToken;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function login(UserLoginRequest $request): JsonResponse
    {
        try {
            $requestData = $request->validated();

            $userData = User::query()
                ->where('username', $requestData['username'])
                ->first();

            if (!$userData || !Hash::check($requestData['password'], $userData->password)) {
                return response()->json([
                    'code' => 400,
                    'message' => 'Invalid username or password'
                ], 400);
            }

            $existingToken = UserToken::query()
                ->where('user_id', $userData->id)
                ->orderBy('expired_at', 'DESC')
                ->first();

            if ($existingToken) {
                if (!now()->greaterThan($existingToken->expired_at)) {
                    return response()->json([
                        'code' => 400,
                        'message' => 'Account is active, please try again later'
                    ], 400);
                }
            }

            $userToken = Str::uuid()->toString();

            $setToken = UserToken::query()->create([
                'user_id' => $userData->id,
                'token' => $userToken,
                'expired_at' => now()->addMinutes(1000)
            ]);

            if ($setToken) {
                return (new UserLoginResource($setToken))->response()->setStatusCode(200);
            }

            return response()->json([
                'code' => 500,
                'message' => 'Failed to login',
            ], 400);
        } catch (Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function profile(): JsonResponse
    {
        try {
            $userData = Auth::user();

            return (new UserProfileResource($userData))->response();
        } catch (Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
