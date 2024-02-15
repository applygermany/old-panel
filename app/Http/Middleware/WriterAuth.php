<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class WriterAuth
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
//	    return $next($request);

        if (auth()->guard('api')->guest()) {
            return response()->json(array("status" => 0, "msg" => "لطفاً ابتدا وارد حساب کاربری خود شوید"));
        } else {
            if (auth()->guard('api')->user()->status == 2 || auth()->guard('api')->user()->verified == 2)
                return response()->json(array("status" => 0, "msg" => "حساب کاربری شما غیر فعال است"));
            if (auth()->guard('api')->user()->level == 1 || auth()->guard('api')->user()->level == 4) {
                return response()->json(array("status" => 0, "msg" => "شما اجازه دسترسی ندارید"));
            }
        }
        return $next($request);
    }
}
