<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Device;

class DeviceAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle($request, Closure $next)
    {
        
        $token = $request->header('Device-Token');

        $device = Device::where('api_token', $token)->first();

        if (!$device) {
            return response()->json(['error' => 'Unauthorized device'], 401);
        }

        // Disponibiliza o device para o controller
        $request->merge(['device' => $device]);

        return $next($request);
    }
}
