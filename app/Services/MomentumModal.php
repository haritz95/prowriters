<?php

declare (strict_types = 1);

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Routing\Route;
use Momentum\Modal\Modal;

class MomentumModal extends Modal
{
    protected function handleRoute(Request $request, Route $route): mixed
    {
        /** @var \Illuminate\Routing\Router */
        $router = app('router');

        $middleware = new SubstituteBindings($router);
        // Added by prowriters
        if (!is_single_language()) {
            request()->route()->forgetParameter('lc');
        }

        return $middleware->handle(
            $request,
            fn() => $route->run()
        );
    }

}
