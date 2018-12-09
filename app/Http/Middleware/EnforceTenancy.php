<?php

namespace App\Http\Middleware;

use Closure;
use Config;
use Log;

/**
 * Class EnforceTenancy.
 *
 * @package App\Http\Middleware
 */
class EnforceTenancy
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (config('app.env') === 'testing') {
            return $next($request);
        }
        Config::set('database.default', 'tenant');
        Config::set('app.url', 'http://' . $request->tenant . '.' . config('app.domain','scool.test'));
        return $next($request);
    }
}
