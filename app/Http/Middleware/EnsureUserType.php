<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $user_type)
    {
        if (auth()->user()->type != $user_type) {
            abort(403, 'Unauthorized action.');
        }
        return $next($request);
    }
}
