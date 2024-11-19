<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomThrottleHandler
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        try {
            return $next($request);
        } catch (ThrottleRequestsException $exception) {
            return response()->json([
                'status' => 'error',
                'message' => 'Too many requests. Please try again later.',
            ], 429);
        }

    }
}
