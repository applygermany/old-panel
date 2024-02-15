<?php

namespace App\Http\Middleware;

use Closure;

class CheckAdminAccess
{

    public $pages = [
        "motivations",
        "dashboard",
        "resumes",
        "users",
        "admins",
        "webinars",
        "financial",
        "pricing",
        "applies",
        "off",
        "universities",
        "settings",
        "foundation",
        "users_information"
    ];
    public $route = [
        "motivations" => "orders",
        "applyLevels" => "applies",
        "applyPhases" => "applies",
        "dashboard" => "dashboard",
        "motivations" => "orders",
        "resumes" => "orders",
        "users" => "users",
        "admins" => "admins",
        "webinars" => "webinars",
        "financial" => "financial",
        "pricing" => "financial",
        "off" => "financial",
        "universities" => "universities",
        "settings" => "settings",
        "foundation" => "settings",
        "users_information" => "users_information",
    ];

    public function handle($request, Closure $next)
    {

        $path = explode("/", str_replace("admin/", "", $request->path()))[0];

        if (!in_array($path, $this->pages)) {
            return $next($request);
        }
        $perm = auth()->user()->admin_permissions;
        $sec = $this->route[$path];
        if (isset($perm->$sec) && $perm->$sec == 1) {
            return $next($request);
        }
        $nextAvailableroute = "";
        foreach ($perm as $key => $per) {
            if ($per == 1) {
                foreach ($this->route as $key1 => $per1) {
                    if ($key == $per1) {
                        $nextAvailableroute = $key1;
                        break;
                    }
                }
            }
        }
        return redirect("/admin/" . $nextAvailableroute);
    }
}
