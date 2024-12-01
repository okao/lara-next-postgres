<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
  public function handle(Request $request, Closure $next, string $permission)
  {
    if (!$request->user() || !$request->user()->hasPermission($permission)) {
      return response()->json([
        'status' => false,
        'message' => 'Unauthorized - Insufficient permissions'
      ], 403);
    }

    return $next($request);
  }
}
