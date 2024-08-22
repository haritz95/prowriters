<?php

use Illuminate\Support\Facades\Route;
use App\PaymentGateways\PayU\PayUController;
use App\PaymentGateways\Stripe\StripeController;
use App\PaymentGateways\Paystack\PaystackController;
use App\PaymentGateways\Braintree\BraintreeController;
use App\PaymentGateways\NowPayments\NowPaymentsController;
use App\PaymentGateways\PaypalCheckout\PaypalCheckoutController;
use App\PaymentGateways\TwoCheckout\TwoCheckoutController;

// Paypal Checkout
Route::prefix('paypal/checkout')->controller(PaypalCheckoutController::class)->group(function () {

    Route::get('/', 'index')
        ->name('paypal_checkout');

    Route::post('process', 'capturePayment')
        ->name('paypal_checkout_process');

    Route::post('generate/token', 'generateToken')
        ->name('paypal_checkout_generate_token');
});

// Stripe
Route::prefix('stripe')->controller(StripeController::class)->group(function () {

    Route::get('/', 'index')->name('stripe');
    Route::post('process', 'capturePayment')->name('stripe_process');
});



//Braintree
Route::prefix('braintree')->controller(BraintreeController::class)->group(function () {

    Route::get('/', 'index')->name('braintree');
    Route::post('process', 'capturePayment')->name('braintree_process');
});


//Paystack
Route::prefix('paystack')->controller(PaystackController::class)->group(function () {

    Route::get('/', 'index')->name('paystack');

    Route::post('/verify', 'verifyPayment')->name('paystack_verify_payment');
});



//Paystack
Route::prefix('payu')->controller(PayUController::class)->group(function () {

    Route::get('/', 'index')->name('payu');

    Route::post('/verify', 'verifyPayment')->name('payu_verify_payment');

    Route::post('/payment-captured', 'paymentCaptured')->name('payu_payment_captured');
});

//2Checkout
Route::prefix('twocheckout')->controller(TwoCheckoutController::class)->group(function () {

    Route::get('/', 'index')->name('twocheckout');

    Route::post('/verify', 'verifyPayment')->name('twocheckout_verify_payment');

    Route::post('/payment-captured', 'paymentCaptured')->name('twocheckout_payment_captured');
});

// NOWPayments
Route::prefix('nowpayments')->controller(NowPaymentsController::class)->group(function () {
    Route::get('/', 'index')->name('nowpayments');
    Route::post('/process', 'createPayment')->name('nowpayments_process');
    Route::post('/verify', 'verifyPayment')->name('nowpayments_verify_payment');
    Route::post('/payment-captured', 'paymentCaptured')->name('nowpayments_payment_captured');
});
