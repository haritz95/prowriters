<?php

namespace App\Providers;

use Laravel\Cashier\Cashier;
use App\Models\CustomerProfile;
use App\Services\InertiaCustomResponse;
use Illuminate\Pagination\Paginator;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Overriding vendor class
        /*
            We need the following piece  of code to 
            allow the application to run on a subdirectory. Currently, by default
            Laravel-inertia cannot handle requests from sub directories. So we are overriding the
            default vendor class with our custom made one
        */        
        $loader = AliasLoader::getInstance();
        $loader->alias(\Inertia\Response::class, InertiaCustomResponse::class);
        // End of Overriding vendor class
      
        Cashier::ignoreMigrations();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        Paginator::useBootstrap();

        Cashier::useCustomerModel(CustomerProfile::class);

        Schema::defaultStringLength(191);

        if (env('ENABLE_HTTPS') == TRUE) {
            \URL::forceScheme('https');
        }

        $mainPath    = database_path('migrations');
        $directories = glob($mainPath . '/*', GLOB_ONLYDIR);
        $paths       = array_merge([$mainPath], $directories);

        $this->loadMigrationsFrom($paths);

    }

}
