<?php
/**
 * API 限制IP
 */
namespace App\Http\Middleware;

use Cache;
use Closure;

class ApiIpMiddleware
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
        $ip = $request->ip();
        if (empty($ip)) {
            abort(403, 'IP Forbidden.');
        }
        return $next($request);
    }
}
