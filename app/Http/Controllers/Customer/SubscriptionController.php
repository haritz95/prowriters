<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Billing\SubscriptionPlan;
use App\Models\CustomerProfile;
use App\Models\Payments\PaymentGateway;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{

    public function index(Request $request)
    {
        return inertia('Customer/Subscriptions/Plans', [
            'data' => [
                'title' => __('Subscription Plans'),
                'plans' => SubscriptionPlan::orderBy('price', 'ASC')->get(),
            ],

        ]);
    }

    public function create(SubscriptionPlan $subscriptionPlan)
    {
        $gateway = PaymentGateway::getByUniqueName('stripe');

        config(['cashier.secret' => $gateway->keys->secret_key]);
        //Stripe::setApiKey($gateway->keys->secret_key);

        $customer = CustomerProfile::where('user_id', auth()->user()->id)->get()->first();

        return inertia('Customer/Subscriptions/Payment', [
            'data' => [
                'title'                   => __('Subscription Payment'),
                'publishable_key'         => $gateway->keys->publishable_key,
                'client_secret'           => $customer->createSetupIntent()->client_secret,
                'urls'                    => [
                    'submit_form' => route('customer.subscriptions.store', $subscriptionPlan->uuid),
                ],              
                'subscription' => $subscriptionPlan->only(['title', 'price'])

            ],

        ]);
    }

    public function store(Request $request, SubscriptionPlan $subscriptionPlan)
    {
        $this->validate($request, [
            'token' => 'required',
        ]);

        try {
            $customer = CustomerProfile::where('user_id', auth()->user()->id)->get()->first();

            $gateway = PaymentGateway::getByUniqueName('stripe');

            config(['cashier.secret' => $gateway->keys->secret_key]);

            $customer->newSubscription('default', $subscriptionPlan->stripe_id)->create($request->token);

            return redirect()->back()->withSuccess(__('Subscription Complete'));
        } catch (\Exception$e) {
            return redirect()->back()->withFail(__('Could not complete the subscription process'));
        }
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'first_name'   => 'required|string|max:255',
            'last_name'    => 'required|string|max:255',
            'email'        => 'required|string|email|max:255|unique:users,email,' . auth()->user()->id,
            'phone'        => 'required|string|max:255',
            'country_code' => 'required',
            'timezone'     => 'required',
        ]);

        $user = auth()->user();
        $user->update($data);

        return redirect()->back()->withSuccess(__('Account updated successfully.'));
    }

}
