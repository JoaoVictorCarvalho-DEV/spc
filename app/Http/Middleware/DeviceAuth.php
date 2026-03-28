<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DeviceAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $token = $request->bearerToken();

        $device = \App\Models\Device::where('api_token', $token)->first();

        if (!$device) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // disponibiliza o device na request
        $request->merge(['device' => $device]);

        return $next($request);
    }
}
