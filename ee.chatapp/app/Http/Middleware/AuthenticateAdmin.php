<?php

namespace App\Http\Middleware;

use App\Common\WhereClause;
use App\Repository\AgentAccountRepositoryInterface;
use App\Utils\AuthUtil;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

class AuthenticateAdmin
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
            return response()->json('token not provided', 401);
        }
        $token = $request->header('Authorization');
        if (Str::startsWith("Bearer ", $token)) {
            return response()->json('token invalid', 401);
        }
        $token = Str::replaceFirst('Bearer ', '', $token);

        $userRepo = App::make(AgentAccountRepositoryInterface::class);
        if (!($userRepo instanceof AgentAccountRepositoryInterface)) {
            return response()->json('System error', 500);
        }

        $user = $userRepo->find([
            WhereClause::query('remember_token', $token)
        ]);
        if (empty($user)) {
            return response()->json('token not found', 401);
        }

        AuthUtil::getInstance()->setModel($user);
        return $next($request);
    }
}
