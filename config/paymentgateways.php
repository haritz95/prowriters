<?php
return [
    /*
    |--------------------------------------------------------------------------
    | Payment Gateways
    |--------------------------------------------------------------------------
    |
    'name' => '',
     The route that will take the user to pay through the gateway
     route' => ''
     The fields that will be fetched from database when loading the settings page
    'fields' => [];
    |
    */

    'gateways' => [
        'paypal_checkout' => [
            'name' => 'Paypal Smart Checkout',
            'folder' => 'PaypalCheckout',
            'route' => 'paypal_checkout',

        ],
        'stripe' => [
            'name' => 'Stripe',
            'folder' => 'Stripe',
            'route' => 'stripe',
        ],
        'braintree' => [
            'name' => 'Braintree',
            'folder' => 'Braintree',
            'route' => 'braintree',
            // 'options' => [],

        ],
        'twocheckout' => [
            'name' => '2Checkout',
            'folder' => 'TwoCheckout',
            'route' => 'twocheckout',
        ],
        'nowpayments' => [
            'name' => 'NOWPayments',
            'folder' => 'NOWPayments',
            'route' => 'nowpayments',
        ],
        // 'paystack' => [
        //     'name' => 'Paystack',
        //     'folder' => 'Paystack',
        //     'route' => 'paystack',
        // ],
        // 'payu' => [
        //     'name' => 'PayU',
        //     'folder' => 'PayU',
        //     'route' => 'payu',
        // ],


    ],
    'namespace' => 'App\PaymentGateways\\',
    'default_gateway' => 'paypal_checkout',
    'module_folder_root' => app_path('PaymentGateways'),
    // 'settings_view' => 'views'. DIRECTORY_SEPARATOR . 'setup',
    'front_view_layout' => 'customer.checkout.front_view_layout',



];
