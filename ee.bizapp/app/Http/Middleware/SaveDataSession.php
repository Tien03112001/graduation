<?php

namespace App\Http\Middleware;

use App\Utils\CookieUtil;
use App\Utils\ECommerceTrackingUtil;
use Closure;
use Illuminate\Support\Facades\Session;

class SaveDataSession
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
        CookieUtil::getCustomerCookie();
        $field = ECommerceTrackingUtil::$LIST_NAME_KEY;
        if ($request->has($field)) {
            Session::put($field, $request->input($field));
        }
        return $next($request);
    }
}
