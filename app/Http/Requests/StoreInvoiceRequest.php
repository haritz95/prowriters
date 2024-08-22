<?php

namespace App\Http\Requests;

use App\Rules\MoneyFormat;
use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceRequest extends FormRequest
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
            'customer_id'                    => 'required',
            'invoice_date'                   => 'required|date:format:' . config('app.date.form_format'),
            'due_date'                       => 'required|date:format:' . config('app.date.form_format'),
            'invoice_items'                  => 'required|array',
            'invoice_items.*.linked_task_id' => 'nullable',
            'invoice_items.*.name'           => 'required|max:192',
            'invoice_items.*.description'    => 'nullable|max:500',
            'invoice_items.*.price'          => 'required',
            'invoice_items.*.quantity'       => 'required',
            'invoice_items.*.sub_total'      => 'required',
            'discount'                       => ['nullable', new MoneyFormat],
            'billing_address'                => 'required|max:500',
            'admin_note'                     => 'required|max:500',
            'customer_note'                  => 'required|max:500',
            'terms_and_conditions'           => 'required|max:800',
        ];

    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'invoice_items.*.name'        => __('Name'),
            'invoice_items.*.description' => __('description'),
            'invoice_items.*.price'       => __('Price'),
            'invoice_items.*.quantity'    => __('Quantity'),
            'invoice_items.*.sub_total'   => __('Sub Total'),
        ];
    }

}
