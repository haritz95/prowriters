<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProcessOfflinePaymentRequest extends FormRequest
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
        $rules = [];
        if ($settings = $this->getSettings()) {
            if ($settings->requires_transaction_number == true) {
                $rules['reference'] = 'required';
            }
            if ($settings->requires_uploading_attachment == true) {
                $rules['files'] = 'required|array';
            }
        }

        return $rules;
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        $attributeNames = [];
        if ($settings = $this->getSettings()) {
            
            if ($settings->requires_transaction_number == true) {
                $attributeNames['reference'] = $settings->reference_field_label;
            }

            if ($settings->requires_uploading_attachment == true) {
                $attributeNames['files'] = $settings->attachment_field_label;
            }
        }

        return $attributeNames;
    }

    private function getSettings()
    {
        return optional($this->payment_method)->settings;
    }
}
