<?php

namespace App\Http\Middleware;

use Closure;

class CheckLogin
{
    public function handle($request, Closure $next)
    {
        if (auth()->user())
        {
            if (auth()->user()->level == 4)
                return redirect()->route('admin.dashboard');
            else
                return redirect('/');
        }
        return $next($request);
    }
}