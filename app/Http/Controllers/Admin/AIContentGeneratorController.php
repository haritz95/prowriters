<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\AIContentGeneratorControllerContract;

class AIContentGeneratorController extends AIContentGeneratorControllerContract
{

    protected function getFolder(): string
    {
        return 'Admin';
    }

    protected function getRoutePrefix(): string
    {
        return 'admin';
    }
}
