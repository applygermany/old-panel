<?php

namespace App\Http\Middleware;

use Closure;

class CheckAdmin
{
    public function handle($request, Closure $next)
    {

        if (!auth()->user())
            return redirect()->route('admin.loginForm');
        if (auth()->user()->level != 4)
            return redirect()->route('admin.loginForm');


        if (auth()->check() && auth()->id() > 1) {
            if (time() - auth()->user()->last_login >= 60 * 60 * 12) {
                auth()->logout();
                return redirect()->route('admin.loginForm');
            }
        }


        return $next($request);
    }
}
