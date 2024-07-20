<?php

namespace App\Http\Middleware;

use App\Models\UserToken;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VerifyUser
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $requestToken = $request->header('Authorization');

        if (empty($requestToken)) {
            return response()->json([
                'code' => 401,
                'message' => 'Unauthorized'
            ])->setStatusCode(401);
        }

        $checkToken = UserToken::query()
            ->where('token', $requestToken)
            ->orderBy('expired_at', 'DESC')
            ->first();

        if (empty($checkToken) || now()->greaterThan($checkToken->expired_at)) {
            return response()->json([
                'code' => 401,
                'message' => 'Unauthorized'
            ])->setStatusCode(401);
        }

        Auth::login($checkToken->user);

        return $next($request);
    }
}
