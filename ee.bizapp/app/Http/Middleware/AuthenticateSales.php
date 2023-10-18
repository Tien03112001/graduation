<?php

namespace App\Http\Middleware;

use App\Models\Account;
use App\Utils\AuthUtil;
use Closure;
use Illuminate\Support\Str;

class AuthenticateSales
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string $role
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$request->hasHeader('Authorization')) {
            return response('token not provided', 401);
        }

        $token = $request->header('Authorization');

        if (Str::startsWith("Bearer ", $token)) {
            return response()->json('token invalid', 401);
        }

        $token = Str::replaceFirst('Bearer ', '', $token);

        $user = Account::where('remember_token', $token)->first();

        if (empty($user)) {
            return response('token invalid', 401);
        }

        AuthUtil::getInstance()->setModel($user);
        return $next($request);
    }
}
