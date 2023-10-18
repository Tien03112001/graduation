<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AuthenticateApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->hasHeader('Authorization')) {
            return response('token not provided', 401);
        }
        $token = $request->header('Authorization');
        if (Str::startsWith("Bearer ", $token)) {
            return response()->json('token format invalid', 401);
        }
        $token = Str::replaceFirst('Bearer ', '', $token);
        if ($token != config('api.token')) {
            return response()->json('token invalid', 401);
        }
        return $next($request);
    }
}
