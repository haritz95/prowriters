<?php

namespace App\PaymentGateways\Paystack;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\PaymentGatewaySettingsService;

class PaystackSettingsController extends Controller
{
    private $uniqueName = 'paystack';

    public function updateSettings(Request $request, PaymentGatewaySettingsService $settingService)
    {
        $request->validate([
            'name' => 'required',
            'keys.public_key' => 'required',
            'keys.secret_key' => 'required',
        ]);

        $settingService->save($this->uniqueName, $request->name, $request->keys, $request->inactive);

        return redirect()->back()->withSuccess(__('Successfully updated'));
    }
}
