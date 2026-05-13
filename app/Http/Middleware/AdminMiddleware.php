<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): JsonResponse
    {
        if (! $request->user()) {
            return response()->json([
                'message' => 'Unauthenticated.'
            ], 401);
        }

        if (! $request->user()->is_admin) {
            return response()->json([
                'message' => 'Access denied. Admins only.'
            ], 403);
        }

        return $next($request);
    }
}