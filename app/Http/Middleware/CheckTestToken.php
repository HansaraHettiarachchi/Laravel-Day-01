<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckTestToken
{
    public function handle(Request $request, Closure $next)
    {
        $expectedToken = '12345678';
        $token = $request->bearerToken() ?? $request->input('token');

        if ($token !== $expectedToken) {
            return response()->json([
                'error' => 'Invalid token',
                'message' => 'The provided token is not valid'
            ], 401);
        }

        return $next($request);
    }
}
