<?php

namespace App\Http\Middleware;

use App\Utils\AuthUtil;
use App\Utils\CryptUtil;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class AuthenticateOwnedWebsite
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
            return response('token invalid', 401);
        }
        $token = Str::replaceFirst('Bearer ', '', $token);

        $user = CryptUtil::getInstance()->decryptData($token);

        if (empty($user)) {
            return response()->json('token not found', 401);
        }

        $expiredAt = Carbon::parse($user['expired_at']);
        if (now()->gt($expiredAt)) {
            return response()->json('token expired', 401);
        }

        AuthUtil::getInstance()->setArrayModel($user);
        return $next($request);
    }
}
