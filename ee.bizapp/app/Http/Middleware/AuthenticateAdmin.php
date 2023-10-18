<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Utils\AuthUtil;
use Closure;

class AuthenticateAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$request->hasHeader('Authorization')) {
            return response('token not provided', 401);
        }

        $token = $request->header('Authorization');
        $user = User::where('remember_token', $token)->first();
        if (empty($user)) {
            return response('token invalid', 401);
        }
        AuthUtil::getInstance()->setModel($user);
        if (empty($user)) {
            return response('token invalid', 401);
        }
        return $next($request);
    }
}
