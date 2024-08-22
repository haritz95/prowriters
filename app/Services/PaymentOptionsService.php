<?php

namespace App\Services;

use App\Models\Payments\OfflinePaymentMethod;
use App\Models\Payments\PaymentGateway;

class PaymentOptionsService
{

    public function all($token)
    {
        return [
            'online'  => $this->online($token),
            'offline' => $this->offline(),
        ];
    }

    public function getOnline($token)
    {
        return [
            'online' => $this->online($token),
        ];
    }

    public function getOffline()
    {
        return [
            'offline' => $this->offline(),
        ];
    }

    private function online($token)
    {
        $gateways = config('paymentgateways')['gateways'];

        if (is_array($gateways) && count($gateways) > 0) {
            $paymentOptions = PaymentGateway::whereNull('inactive')->get();

            if ($paymentOptions->count() > 0) {

                foreach ($paymentOptions as $paymentOption) {

                    if (array_key_exists($paymentOption->unique_name, $gateways)) {

                        $gateway = $gateways[$paymentOption->unique_name];

                        $paymentOption->url = route($gateway['route'], ['token' => $token]);

                        $data[] = $paymentOption;
                    }
                }

                return (object) $data;
            }
        }

        return NULL;
    }

    private function offline()
    {
        $paymentOptions = OfflinePaymentMethod::whereNull('inactive')->get();

        return ($paymentOptions->count() > 0) ? $paymentOptions : null;
    }
}
