<?php

namespace App\Http\Middleware;

use App\Enums\PermissionType;
use App\Enums\UserType;
use Closure;
use Illuminate\Http\Request;

class SuperAdminOnly
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
        if (auth()->user()->type != UserType::ADMIN) {
            abort(403, 'Unauthorized action. Super Admin only');
        }

        if (!(auth()->user()->hasRole(PermissionType::ROLE_SUPER_ADMIN))) {
            abort(403, 'Unauthorized action. Super Admin only');
        }
        return $next($request);
    }
}
