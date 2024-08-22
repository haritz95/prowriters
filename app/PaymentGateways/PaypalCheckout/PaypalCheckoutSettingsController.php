<?php

namespace App\PaymentGateways\PaypalCheckout;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\PaymentGatewaySettingsService;

class PaypalCheckoutSettingsController extends Controller
{

    private $uniqueName = 'paypal_checkout';

    public function updateSettings(Request $request, PaymentGatewaySettingsService $settingService)
    {
        $request->validate([
            'name' => 'required',
            'keys.environment' => 'required',
            'keys.client_id' => 'required',
            'keys.client_secret' => 'required',
        ]);

        $settingService->save($this->uniqueName, $request->name, $request->keys, $request->inactive);

        return redirect()->back()->withSuccess('Successfully updated');
    }
}
