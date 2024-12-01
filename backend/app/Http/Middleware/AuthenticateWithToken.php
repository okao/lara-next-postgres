<?php

namespace App\Http\Middleware;

use App\Services\TokenService;
use Closure;
use Illuminate\Http\Request;

class AuthenticateWithToken
{
  public function __construct(private TokenService $tokenService) {}

  public function handle(Request $request, Closure $next)
  {
    $token = $request->bearerToken();

    if (!$token) {
      return response()->json([
        'status' => false,
        'message' => 'Unauthorized - No token provided'
      ], 401);
    }

    $user = $this->tokenService->validateToken($token);

    if (!$user) {
      return response()->json([
        'status' => false,
        'message' => 'Unauthorized - Invalid token'
      ], 401);
    }

    $request->setUserResolver(fn() => $user);

    return $next($request);
  }
}
