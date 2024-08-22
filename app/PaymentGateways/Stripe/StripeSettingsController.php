<?php

namespace App\PaymentGateways\Stripe;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\PaymentGatewaySettingsService;

class StripeSettingsController extends Controller
{
    private $uniqueName = 'stripe';

    public function updateSettings(Request $request, PaymentGatewaySettingsService $settingService)
    {
        $request->validate([
            'name' => 'required',
            'keys.publishable_key' => 'required',
            'keys.secret_key' => 'required',
        ]);

        $settingService->save($this->uniqueName, $request->name, $request->keys, $request->inactive);

        return redirect()->back()->withSuccess('Successfully updated');
    }
}
