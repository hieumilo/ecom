<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $is_admin = $user->is_admin;
        
        if (!$is_admin) {
            return Response(trans('setting.message.fails.login.not_administrator'), 400);
        }

        return $next($request);
    }
}
