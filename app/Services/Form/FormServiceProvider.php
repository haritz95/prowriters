<?php

namespace App\Services\Form;

use Illuminate\Support\ServiceProvider;
use App\Services\Form\FormService;

class FormServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('form', function () {

            return new FormService();
        });
    }
}
