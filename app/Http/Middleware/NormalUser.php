<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class NormalUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->guard('api')->guest()) {
            return response()->json(array("status"=>0,"msg"=>"لطفاً ابتدا وارد حساب کاربری خود شوید"));
        } else {
            if (auth()->guard('api')->user()->type != 1) {
                return response()->json(array("status"=>0,"msg"=>"شما اجازه دسترسی ندارید"));
            }
        }
        return $next($request);
    }
}
