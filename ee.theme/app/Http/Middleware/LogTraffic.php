<?php

namespace App\Http\Middleware;

use App\Utils\LoggingUtil;
use Closure;
use Illuminate\Http\Request;

class LogTraffic
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        LoggingUtil::getInstance()->log($request);
        return $next($request);
    }
}
