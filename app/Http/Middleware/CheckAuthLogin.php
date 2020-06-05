<?php

namespace App\Http\Middleware;

use Closure;

class CheckAuthLogin
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
        if (auth('web')->check()) {
            return \redirect('/friend/roster');
        }
        return $next($request);
    }
}
