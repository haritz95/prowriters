<?php

namespace App\PaymentGateways\PaypalCheckout;

use App\Http\Controllers\Payments\PaymentGatewayController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;

class PaypalCheckoutController extends PaymentGatewayController
{
    private $client;

    public function __construct()
    {
        parent::__construct('paypal_checkout');

        $this->client = new PayPalHttpClient($this->environment());
    }

    public function index(Request $request)
    {
        return inertia($this->getPaymentView(), [
            'data' => [
                'total'        => $this->getTotal(),
                'gateway_name' => $this->gateway->name,
                'client_id'    => $this->gateway->keys->client_id,
                'currency'     => $this->getCurrency(),
                'urls'         => [
                    'choose_payment_method' => route('choose_payment_method', ['token' => $request->token]),
                    'generate_token'        => route('paypal_checkout_generate_token', ['token' => $request->token]),
                    'capture_payment'        => route('paypal_checkout_process', ['token' => $request->token]),
                ],
            ],
        ]);

    }

    public function generateToken(Request $request)
    {        
        $orderRequest = new OrdersCreateRequest();
        // $orderRequest->prefer('return=representation');
        $orderRequest->body = [
            "intent"         => "CAPTURE",
            "purchase_units" => [
                [
                    "amount" => [
                        "value"         => $this->getTotal(),
                        "currency_code" => $this->getCurrency(),
                    ],
                ],
            ],
        ];

        $error = null;
        try {
            // Call API with your client and get a response for your call
            $response = (object) $this->client->execute($orderRequest);

            if ($this->isTokenGenerationSuccessful($response)) {
                return response()->json([
                    'status' => 'success',
                    'id'     => $response->result->id,
                ]);
            }
        } catch (\PayPalHttp\HttpException | \Exception$e) {
            $error = $e->getMessage();
        }

        if ($error) {
            $this->logError($error, 'While generating token');
        }

        return response()->json([
            'status'  => 'failure',
            'message' => 'Please try again later, or use a different payment method',
        ]);
    }

    public function capturePayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $request = new OrdersCaptureRequest($request->order_id);
        // $request->prefer('return=representation');
        $error = null;

        try {

            $response = (object) $this->client->execute($request);

            if ($this->isCaptureSuccessful($response)) {

                // Record the Payment Information
                $token = $this->savePaymentRecords($this->getCapturedAmount($response), $response->result->id);

                return $this->redirectOnSuccess($token);
            }
        } catch (\PayPalHttp\HttpException | \Exception$e) {
            $error = $e->getMessage();
        }

        if ($error) {
            $this->logError($error, 'While capturing payment');
        }

        return $this->redirectOnFail();
    }

    private function getCapturedAmount($response)
    {
        return $response->result->purchase_units[0]->payments->captures[0]->amount->value;
    }
    private function isTokenGenerationSuccessful($response)
    {
        if (isset($response->result->status) && $response->result->status == 'CREATED' && isset($response->result->id) && $response->result->id) {
            return true;
        }
    }

    private function isCaptureSuccessful($response)
    {
        if (isset($response->result->status) && $response->result->status == 'COMPLETED' && isset($response->result->id) && $response->result->id) {
            return true;
        }
    }

    private function environment()
    {
        $clientId     = $this->gateway->keys->client_id;
        $clientSecret = $this->gateway->keys->client_secret;

        if ($this->gateway->keys->environment == 'production') {
            return new ProductionEnvironment($clientId, $clientSecret);
        } else {
            return new SandboxEnvironment($clientId, $clientSecret);
        }
    }

}
