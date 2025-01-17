<?php

namespace App\PaymentGateways\Stripe;

use App\Http\Controllers\Payments\PaymentGatewayController;
use Illuminate\Http\Request;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class StripeController extends PaymentGatewayController
{
    public function __construct()
    {
        parent::__construct('stripe');

        Stripe::setApiKey($this->gateway->keys->secret_key);
    }

    public function index(Request $request)
    {
        $error = null;

        try {
            return inertia($this->getPaymentView(), [
                'data' => [
                    'total' => $this->getTotal(),
                    'gateway_name' => $this->gateway->name,
                    'publishable_key' => $this->gateway->keys->publishable_key,
                    'urls' => [
                        'choose_payment_method' => route('choose_payment_method', ['token' => $request->token]),
                        'stripe_process' => route('stripe_process', ['token' => $request->token])
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            $error = $e->getMessage();

        }

        if ($error) {
            $this->logError($error, 'While generating token');
        }

        return $this->redirectOnFailedTokenGeneration();
    }

    public function capturePayment(Request $request)
    {
        $intent = null;
        try {
            if (isset($request->payment_method_id)) {
                # Create the PaymentIntent
                $intent = PaymentIntent::create([
                    'payment_method' => $request->payment_method_id,
                    'amount' => $this->convertToCent($this->getTotal()),
                    'currency' => strtolower($this->getCurrency()),
                    'confirmation_method' => 'manual',
                    'confirm' => true,
                ]);
            }
            if (isset($request->payment_intent_id)) {
                $intent = PaymentIntent::retrieve(
                    $request->payment_intent_id
                );
                $intent->confirm();
            }
            $res = $this->generateResponse($intent);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            # Display error on client
            $res = [
                'error' => $e->getMessage(),
            ];
        }

        return response()->json($res);
    }

    public function generateResponse($intent)
    {
        # Note that if your API version is before 2019-02-11, 'requires_action'
        # appears as 'requires_source_action'.
        if (($intent->status == 'requires_action' || $intent->status == 'requires_source_action') &&
            $intent->next_action->type == 'use_stripe_sdk'
        ) {
            # Tell the client side to handle the action
            return [
                'requires_action' => true,
                'payment_intent_client_secret' => $intent->client_secret,
            ];
        } else if ($intent->status == 'succeeded') {
            # The payment didn’t need any additional actions and completed!
            # Handle post-payment fulfillment
            $token = $this->savePaymentRecords($this->getTotal(), $intent->id);
            return [
                "success" => true,
                'redirect_url' => $this->urlToRedirectOnSuccess($token)
            ];
        } else {
            # Invalid status
            return ['error' => 'Could not process your payment'];
        }
    }

    private function convertToCent($amount)
    {
        // Stripe accepts the amount as cents only.
        return intval($amount * 100);
    }
}
