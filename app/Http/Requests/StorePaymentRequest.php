<?php

namespace App\Http\Requests;

use App\Rules\MoneyFormat;
use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'customer_id'   => 'required',
            'method'        => 'required|max:192',
            'amount'        => ['required', new MoneyFormat],
            'reference'     => 'nullable',
            'internal_note' => 'nullable|max:500',
            'customer_note' => 'nullable|max:500',
            'date'          => 'required|date:format:' . config('app.date.form_format'),

        ];
    }

    public function attributes()
    {
        return [
            'customer_id'   => __('Customer'),
            'method'        => __('Payment Method'),
            'amount'        => __('Amount'),
            'reference'     => __('Reference'),
            'internal_note' => __('Internal Note'),
            'customer_note' => __('Customer Note'),
            'date'          => __('Date'),

        ];
    }

}
