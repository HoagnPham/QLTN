<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Services\Auth\JwtService;
use Closure;
use Illuminate\Http\Request;

class AuthenticateJwt
{
    public function __construct(private readonly JwtService $jwtService)
    {
    }

    public function handle(Request $request, Closure $next)
    {
        $header = $request->header('Authorization', '');
        $token = str_starts_with($header, 'Bearer ') ? substr($header, 7) : null;

        if (!$token) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $payload = $this->jwtService->decodeToken($token);
        if (!$payload) {
            return response()->json(['message' => 'Invalid token'], 401);
        }

        $user = User::query()->with('role')->find($payload['sub']);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 401);
        }

        $request->attributes->set('auth_user', $user);

        return $next($request);
    }
}
