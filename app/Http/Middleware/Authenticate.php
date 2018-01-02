<?php

namespace App\Http\Middleware;

use Closure;
use Cookie;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class Authenticate {
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @param  string|null  $guard
	 * @return mixed
	 */
	public function handle($request, Closure $next, $guard = null) {
        $user_info  = Auth::guard($guard)->user();
        if (empty($user_info)){
            return response()->json(["status" => false, "errors" => "Unauthorized"]);
        }
        $request["business_info"] = $user_info;
        return $next($request);
		if (Auth::guard($guard)->guest()) {
			if ($request->ajax() || $request->wantsJson() || substr($request->path(), 0, 3) == "api") {
				return response()->json(["status" => false, "errors" => "Unauthorized."]);
			} else {
				return redirect()->guest('/');
			}
		}
		$user_info  = Auth::guard($guard)->user();
		$domain = $user_info->domain;
		$request_host = $request->server->get("HTTP_HOST");
		if ($domain != $request_host){
            return response()->json(["status" => false, "errors" => "Domain Unauthorized."]);
        }
        $request["business_info"] = $user_info;
		return $next($request);
	}

}
