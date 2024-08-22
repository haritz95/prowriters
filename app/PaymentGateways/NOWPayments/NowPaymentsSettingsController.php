<?php

namespace App\PaymentGateways\NowPayments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\PaymentGatewaySettingsService;

class NowPaymentsSettingsController extends Controller
{

    private $uniqueName = 'nowpayments';

    public function updateSettings(Request $request, PaymentGatewaySettingsService $settingService)
    {
        $request->validate([
            'name' => 'required',
            'keys.environment' => 'required',
            'keys.api_key' => 'required',
            'keys.public_key' => 'required',
        ]);


        $settingService->save($this->uniqueName, $request->name, $request->keys, $request->inactive);

        return redirect()->back()->withSuccess('Successfully updated');
    }
}
