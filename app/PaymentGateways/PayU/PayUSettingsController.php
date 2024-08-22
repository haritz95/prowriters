<?php

namespace App\PaymentGateways\PayU;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\PaymentGatewaySettingsService;

class PayUSettingsController extends Controller
{
    private $uniqueName = 'payu';

    public function updateSettings(Request $request, PaymentGatewaySettingsService $settingService)
    {
        $request->validate([
            'name' => 'required',
            'keys.environment' => 'required',
            'keys.merchant_key' => 'required',
            'keys.merchant_salt' => 'required',
        ]);

        $settingService->save($this->uniqueName, $request->name, $request->keys, $request->inactive);

        return redirect()->back()->withSuccess('Successfully updated');
    }
}
