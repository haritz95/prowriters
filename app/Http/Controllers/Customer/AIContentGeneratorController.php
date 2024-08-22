<?php

namespace App\Http\Controllers\Customer;

use App\Contracts\AIContentGeneratorControllerContract;

class AIContentGeneratorController extends AIContentGeneratorControllerContract
{

    protected function getFolder(): string
    {
        return 'Customer';
    }

    protected function getRoutePrefix(): string
    {
        return 'customer';
    }
}
