<?php

use App\Http\Controllers\Customer\BidRequestController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Customer\TaskController;
use Illuminate\Support\Facades\Route;

// Order Checkout
Route::get('bidRequest/services', [BidRequestController::class, 'servicesList'])->name('public.bidRequests.services');

Route::get('order', [TaskController::class, 'servicesList'])->name('customer.tasks.create');
Route::get('order/services/{service}', [TaskController::class, 'create'])->name('customer.tasks.service');
Route::post('order/services/{service}', [TaskController::class, 'store'])->name('customer.tasks.store');

Route::get('checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::delete('checkout/{id}', [CheckoutController::class, 'destroyCartItem'])->name('cart.destroy.item');
Route::post('checkout/loginOrRegister', [CheckoutController::class, 'loginOrRegister'])->name('checkout.loginOrRegister');

Route::post('coupon/verify', [CheckoutController::class, 'verifyCoupon'])->name('coupons.verify');
Route::post('coupon/remove', [CheckoutController::class, 'removeCoupon'])->name('coupons.remove');

Route::post('checkout', [CheckoutController::class, 'proceedToPayment'])->name('proceed_to_payment');

Route::prefix('checkout/payment')
    ->middleware(['auth', 'verified', 'check_checkout_token'])
    ->group(function () {

        Route::controller(CheckoutController::class)->group(function () {

            Route::get('method', 'choosePaymentMethod')->name('choose_payment_method');

            Route::get('online/success', 'handleSuccessfulOnlinePayment')->name('handle_successful_online_payment');

            Route::get('offline/{payment_method}', 'payUsingOfflineMethod')->name('pay_with_offline_method');

            Route::post('offline/{payment_method}', 'processOfflinePayment')->name('process_pay_with_offline_method');

            Route::get('offline/information/received', 'offlinePaymentSuccess')->name('offline_payment_success');

            Route::get('wallet', 'processWalletPayment')->name('pay_with_wallet');

            Route::get('payment/success', 'paymentSuccess')->name('payment.successful');
        });

        load_route('payment_gateways');
    });
