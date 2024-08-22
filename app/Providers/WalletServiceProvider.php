<?php

namespace App\Providers;

use App\Services\WalletService;
use App\Models\Payments\Payment;
use App\Models\Accounting\Invoice;
use Illuminate\Support\ServiceProvider;
use App\Models\Wallets\WalletAdjustment;
use Illuminate\Database\Eloquent\Relations\Relation;

class WalletServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Services\WalletService', function ($app, $params) {
            return new WalletService($params['walletOwnerModel']);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::morphMap([
            'payment'    => Payment::class,
            'invoice'    => Invoice::class,
            'wallet_adjustment' => WalletAdjustment::class,
            
            // We cannot add App\Models\User as it is being used in Spatie Permission
        ]);
    }
}
