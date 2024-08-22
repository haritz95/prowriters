<?php

namespace App\Providers;

use App\Services\MomentumModal;
use Illuminate\Contracts\Support\Arrayable;
use Inertia\ResponseFactory;
use Momentum\Modal\ModalServiceProvider;

class MomentumModalServiceProvider extends ModalServiceProvider
{

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        ResponseFactory::macro('modal', function (
            string $component,
            array | Arrayable $props = []
        ) {
            return new MomentumModal($component, $props);
        });

        $this->registerCompatibilityMacros();
    }
}
