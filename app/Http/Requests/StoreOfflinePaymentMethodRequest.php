<?php

namespace App\Http\Requests;

use Illuminate\Support\Str;
use Illuminate\Foundation\Http\FormRequest;

class StoreOfflinePaymentMethodRequest extends FormRequest
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

    protected function prepareForValidation()
    {
        $this->merge([
            'slug' => Str::slug($this->name),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->method()) {
            case 'PATCH':
                $nameRule = 'required|unique:offline_payment_methods,id,' . $this->id;
                break;
            default:
                $nameRule = "required|unique:offline_payment_methods";
                break;
        }

        return [
            'slug' => 'nullable',
            'name' => $nameRule,
            'description' => 'required',
            'instruction' => 'nullable|max:500',
            'success_message' => 'required|max:255',
            'inactive' => 'nullable|boolean',
            'settings.requires_transaction_number' => 'nullable|boolean',
            'settings.reference_field_label' => 'required_if:settings.requires_transaction_number,true|max:192',
            'settings.requires_uploading_attachment' => 'nullable|boolean',
            'settings.attachment_field_label' => 'required_if:settings.requires_uploading_attachment,true|max:192',

        ];
    }

    public function attributes()
    {
        return [
            'name' => __('Name'),
            'description' => __('Description'),
            'instruction' => __('Instruction'),
            'success_message' => __('Success Message'),
            'inactive' => __('Inactive'),
            'settings.requires_transaction_number' => __('Requires Transaction Number'),
            'settings.reference_field_label' => __('Reference Field Label'),
            'settings.requires_uploading_attachment' => __('Requires Uploading Attachment'),
            'settings.attachment_field_label' => __('Attachment Field Label'),

        ];
    }

    public function messages()
    {
        return [
            'settings.reference_field_label.required_if' => __('Field name to display for entering transaction number') . ' ' . __('is required'),
            'settings.attachment_field_label.required_if' =>  __('Field name to display for attachment uploading') . ' ' . __('is required'),
        ];
    }
}
