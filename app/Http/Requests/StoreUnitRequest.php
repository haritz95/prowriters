<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUnitRequest extends FormRequest
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
            'units'              => 'required|array',
            'units.*.id'         => 'nullable',
            'units.*.name'       => [Rule::requiredIf($this->unitHasRecords()), 'max:192'],
            'units.*.urgency_id' => [Rule::requiredIf($this->unitHasRecords())],
            'units.*.price'      => [Rule::requiredIf($this->unitHasRecords())],
            'units.*.quantity'   => [Rule::requiredIf($this->unitHasRecords())],

        ];

    }

    private function unitHasRecords()
    {
        return (is_array($this->units) && count($this->units) > 0);
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'units'              => __('Unit'),
            'units.*.name'       => __('Name'),
            'units.*.urgency_id' => __('Urgency'),
            'units.*.price'      => __('Price'),
            'units.*.quantity'   => __('Quantity'),

        ];
    }

}
