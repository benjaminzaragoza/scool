<?php

namespace App\Http\Middleware;

use App\Models\User;
use Auth;
use Cache;
use Carbon\Carbon;
use Closure;

class LogLastUserActivity
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
        if(Auth::check()) {
            $expiresAt = Carbon::now()->addMinutes(5);
            Cache::put(User::USERS_CACHE_KEY. '-user-is-online-' . Auth::user()->id, Carbon::now(), $expiresAt);
        }
        return $next($request);
    }
}
