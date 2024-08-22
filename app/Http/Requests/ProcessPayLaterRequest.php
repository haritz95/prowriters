<?php

namespace App\Http\Requests;

use App\Enums\CartType;
use App\Services\CartService;
use Illuminate\Foundation\Http\FormRequest;

class ProcessPayLaterRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [

        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            if ($message = $this->isNotAllowed()) {
                $validator->errors()->add('some_field', 'does not matter');
                // Set error flash error message
                request()->session()->flash('fail', $message);
            }

        });

    }

    private function isNotAllowed()
    {
        $request = request();

        if (!isset($request->token)) {
            return __('Invalid request');
        }

        $cart = app()->make(CartService::class);

        $userCart = $cart->getSavedCart($request->token, auth()->user()->id);

        if (!isset($userCart->type)) {
            return __('Invalid request');
        }

        if ($userCart->type != CartType::ORDER) {
            return __('You can choose the pay later option only when placing orders');
        }

        if (empty($userCart->total)) {
            return __('Your cart is empty');
        }

        $profile = auth()->user()->customerProfile()->get()->first();

        // if pay later/ credit order is not allowed
        if (!$profile->allow_paying_later) {
            return __('You are not allowed to pay later');
        }

        // $wallet_balance = Invoice::whereNot('invoice_status_id', InvoiceStatusType::FORWARDED)->where('customer_id', $user->id)->sum(DB::raw('(total - amount_paid)'));
        // $total          = $userCart->total;

        // /*
        // Check credit limit
        //  */
        // $balance_remaining_after_order = $wallet_balance - $total;
        // // Remove the negative amount
        // $absolute_value_of_wallet_balance_after_order = abs($balance_remaining_after_order);

        // if ($absolute_value_of_wallet_balance_after_order > $profile->credit_limit) {
        //     // credit limit crossed
        //     return __('app.credit_limit_crossed', ['amount' => format_money($profile->credit_limit)]);
        // }
    }

}
