<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use App\Models\Payments\PaymentGateway;
use Illuminate\Support\Facades\Log;

class PaymentGatewayController extends Controller
{

    protected $cart;

    protected $gateway;

    protected $gatewayUniqueName;

    private $token;

    public function __construct($gatewayUniqueName)
    {
        $this->gatewayUniqueName = $gatewayUniqueName;
        $this->gateway = PaymentGateway::getByUniqueName($gatewayUniqueName);

        if (empty($this->gateway)) {
            abort(401, 'Invalid Payment Processor');
        }

        $this->token = request()->get('token');

        $this->cart = app()->make('App\Services\CartService');
    }

    protected function logError($error, $when)
    {
        Log::debug('payment_gateway_error', [
            'user_id' => auth()->user()->id,
            'gateway' => $this->gateway->unique_name,
            'when'  => $when,
            'message' => $error
        ]);
    }

    protected function getPaymentView()
    {
        $gatewayFolder = config('paymentgateways.gateways.' . $this->gatewayUniqueName . '.folder');
        return 'Checkout/PaymentGateways/' . $gatewayFolder . '/Checkout';
    }

    protected function redirectOnFailedTokenGeneration()
    {
        return redirect()->route('choose_payment_method', ['token' => $this->token])->withFail(__('Please try a different payment option'));
    }

    protected function redirectOnSuccess($token)
    {
        return redirect()->route('handle_successful_online_payment', ['token' => $token]);
    }

    protected function urlToRedirectOnSuccess($token)
    {
        return route('handle_successful_online_payment', ['token' => $token]);
    }

    protected function redirectOnFail()
    {
        return redirect()->route('choose_payment_method', ['token' => $this->token])->withFail(__('Could not process your payment. Please try again'));
    }

    /**
     * Record payment details.
     *
     * @param  float $amount
     * @param  string  $transactionReference
     * @return string
     */
    protected function savePaymentRecords($amount, $transactionReference)
    {
        $paymentRecordService = app()->make('App\Services\PaymentRecordService');

        // Record the Payment Information
        $payment = $paymentRecordService->store(auth()->user()->id, $this->gateway->name, $amount, $transactionReference);

        // // Mark in the cart that payment has been made
        // $token = bin2hex(random_bytes(5));
        $this->cart->markPaymentAsComplete($this->token, $payment->id);

        return $this->token;
    }

    /**
     * Return the total amount of the cart
     *
     * @return string
     */
    protected function getTotal()
    {
        return round($this->cart->getTotalFromSavedCart($this->token), 2, PHP_ROUND_HALF_UP);
    }

    protected function getCurrency()
    {
        return $this->cart->getCurrency();
    }
}
