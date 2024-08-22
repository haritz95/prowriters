<?php

namespace App\PaymentGateways\NowPayments;

use App\Http\Controllers\Payments\PaymentGatewayController;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class NowPaymentsController extends PaymentGatewayController
{
    private $client;
    private $apiKey;
    private $baseUrl;

    public function __construct()
    {
        parent::__construct('nowpayments');

        $this->apiKey = $this->gateway->keys->api_key;
        $this->baseUrl = $this->gateway->keys->environment === 'production'
            ? 'https://api.nowpayments.io/v1'
            : 'https://api-sandbox.nowpayments.io/v1';

        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'headers' => [
                'x-api-key' => $this->apiKey,
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    public function index(Request $request)
    {
        $cryptocurrencies = $this->getAvailableCryptocurrencies();

        return inertia($this->getPaymentView(), [
            'data' => [
                'total' => $this->getTotal(),
                'gateway_name' => $this->gateway->name,
                'currency' => $this->getCurrency(),
                'cryptocurrencies' => $cryptocurrencies,
                'selected_currency' => $request->input('selected_currency', 'BTC'),
                'urls' => [
                    'nowpayments_process' => route('nowpayments_process'),
                ],
            ],
        ]);
    }

    public function getAvailableCryptocurrencies()
    {
        try {
            // Realiza la solicitud al endpoint correcto
            $response = $this->client->get('https://api.nowpayments.io/v1/merchant/coins');
            $responseData = json_decode($response->getBody(), true);

            // Loguear la respuesta completa para depuración
            Log::info('NowPayments API Response:', $responseData);

            // Verifica si la clave 'selectedCurrencies' existe en la respuesta
            if (isset($responseData['selectedCurrencies'])) {
                // Convertir el array de strings en un array de objetos con 'currency' y 'name'
                $cryptocurrencies = array_map(function ($currency) {
                    return [
                        'currency' => $currency,
                        'name' => strtoupper($currency),  // Convertir a mayúsculas o hacer más descriptivo
                    ];
                }, $responseData['selectedCurrencies']);

                return $cryptocurrencies;
            } else {
                // Si la clave 'selectedCurrencies' no existe, loguear el error y retornar un array vacío
                Log::error('NowPayments API Error: "selectedCurrencies" key not found in the response');
                return [];
            }
        } catch (\Exception $e) {
            Log::error('NowPayments API Error: ' . $e->getMessage());
            return [];  // En caso de error, devolver un array vacío
        }
    }


    public function createPayment(Request $request)
    {
        Log::info('Received request to create payment:', $request->all());

        $validatedData = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|string',
            'order_id' => 'required|string',
            'order_description' => 'required|string',
            'crypto_currency' => 'required|string',
        ]);

        Log::info('Validated data:', $validatedData);

        $paymentData = [
            'price_amount' => $validatedData['amount'],
            'price_currency' => $validatedData['currency'],
            'pay_currency' => $validatedData['crypto_currency'],
            'order_id' => $validatedData['order_id'],
            'order_description' => $validatedData['order_description'],
            'ipn_callback_url' => route('nowpayments.ipn'),
            'success_url' => route('payment.success'),
            'cancel_url' => route('payment.cancel'),
        ];

        Log::info('Payment data being sent to NowPayments:', $paymentData);

        try {
            // Especificar la URL completa en la solicitud POST
            $response = $this->client->post('https://api.nowpayments.io/v1/payment', [
                'json' => $paymentData,
            ]);

            $responseBody = $response->getBody()->getContents();
            $responseData = json_decode($responseBody, true);

            Log::info('NowPayments API Response:', ['response' => $responseData]);

            if (isset($responseData['invoice_url'])) {
                return response()->json(['invoice_url' => $responseData['invoice_url']]);
            } else {
                Log::error('Payment creation failed: invoice_url not found in response.', ['response' => $responseData]);
                return response()->json(['error' => 'Payment creation failed: invoice_url not found in response.'], 500);
            }
        } catch (\Exception $e) {
            Log::error('Payment creation failed:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Payment creation failed: ' . $e->getMessage()], 500);
        }
    }
}
