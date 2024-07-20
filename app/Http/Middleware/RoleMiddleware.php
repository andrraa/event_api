<?php

namespace App\Http\Middleware;

use App\Http\Resources\UserProfileResource;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next, $roles): Response
    {
        $userData = new UserProfileResource(Auth::user());

        $userRoleId = $userData->role->id;

        $arrayRole = array_map('intval', explode(';', $roles));

        if (!in_array($userRoleId, $arrayRole)) {
            return response()->json([
                'code' => 403,
                'message' => 'Forbidden'
            ])->setStatusCode(403);
        }

        return $next($request);
    }
}
