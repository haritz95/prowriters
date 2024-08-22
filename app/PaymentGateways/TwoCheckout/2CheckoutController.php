<?php

namespace App\PaymentGateways\TwoCheckout;

use App\Http\Controllers\Payments\PaymentGatewayController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;

class TwoCheckoutController extends PaymentGatewayController
{
    private $client;

    public function __construct()
    {
        parent::__construct('twocheckout');
    }

    public function index(Request $request)
    {
        return inertia($this->getPaymentView(), [
            'data' => [
                'total'        => $this->getTotal(),
                'gateway_name' => $this->gateway->name,
                'client_id'    => $this->gateway->keys->client_id,
                'currency'     => $this->getCurrency(),
            ],
        ]);
    }
}
