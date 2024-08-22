<?php

use Illuminate\Support\Facades\Route;
use App\PaymentGateways\PayU\PayUSettingsController;
use App\PaymentGateways\Stripe\StripeSettingsController;
use App\PaymentGateways\Paystack\PaystackSettingsController;
use App\Http\Controllers\Admin\OfflinePaymentMethodController;
use App\PaymentGateways\Braintree\BraintreeSettingsController;
use App\PaymentGateways\NowPayments\NowPaymentsSettingsController;
use App\PaymentGateways\PaypalCheckout\PaypalCheckoutSettingsController;
use App\PaymentGateways\TwoCheckout\TwoCheckoutSettingsController;

Route::prefix('settings')->name('settings.')
    ->group(function () {

        Route::prefix('payment/gateways/offline')
            ->controller(OfflinePaymentMethodController::class)
            ->group(function () {

                Route::get('', 'index')
                    ->name('offline.payment.methods.index');

                Route::get('create', 'create')
                    ->name('offline.payment.methods.create');

                Route::post('create', 'store')
                    ->name('offline.payment.methods.store');

                Route::get('{method}/edit', 'edit')
                    ->name('offline.payment.methods.edit');

                Route::patch('{method}/edit', 'update')
                    ->name('offline.payment.methods.update');

                Route::delete('{method}', 'destroy')
                    ->name('offline.payment.methods.destroy');
            });



        Route::patch('stripe/configure', [StripeSettingsController::class, 'updateSettings'])
            ->name('update.stripe');

        Route::patch('braintree/configure', [BraintreeSettingsController::class, 'updateSettings'])
            ->name('update.braintree');

        Route::patch('paypal/checkout/configure', [PaypalCheckoutSettingsController::class, 'updateSettings'])
            ->name('update.paypal_checkout');

        Route::patch('paystack/configure', [PaystackSettingsController::class, 'updateSettings'])
            ->name('update.paystack');

        Route::patch('payu/configure', [PayUSettingsController::class, 'updateSettings'])
            ->name('update.payu');

        Route::patch('twocheckout/configure', [TwoCheckoutSettingsController::class, 'updateSettings'])
            ->name('update.twocheckout');

        Route::patch('nowpayments/configure', [NowPaymentsSettingsController::class, 'updateSettings'])
            ->name('update.nowpayments');
    });
