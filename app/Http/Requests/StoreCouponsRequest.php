<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class StoreCouponsRequest extends FormRequest
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

        switch ($this->method()) {
            case 'PATCH':
                $codeRule = 'required|unique:coupons,id,' . $this->id;
                break;
            default:
                $codeRule = "required|unique:coupons";
                break;
        }

        $rules = [
            'type'                   => 'required',
            'code'                   => $codeRule,
            'description'            => 'nullable|max:192',
            'amount'                 => 'required|numeric|gt:0',
            'active_date'            => 'required|date:format:Y-m-d\TH:i:sP',
            'expiry_date'            => 'nullable|date:format:Y-m-d\TH:i:sP|after_or_equal:active_date',
            'minimum_spend'          => 'required|numeric|gt:amount',
            'maximum_discount'       => 'nullable|required_if:type,percentage|numeric|gt:0',
            'usage_limit_per_coupon' => 'nullable|integer',
            'usage_limit_per_user'   => 'nullable|integer',
            'specific_customer_only' => 'nullable|boolean',
            'customer_id'            => 'nullable|required_if:specific_customer_only,1',
            'first_order_only'       => 'nullable|boolean',
            'inactive'               => 'nullable|boolean',
            'archive'                => 'nullable|boolean',

        ];
        return $rules;
    }

    protected function passedValidation()
    {
        $data = [
            'active_date' => Carbon::parse($this->active_date)->setTimezone('UTC')->format('Y-m-d H:i:s'),
            'expiry_date' => (is_null($this->expiry_date)) ? null : Carbon::parse($this->expiry_date)->setTimezone('UTC')->format('Y-m-d H:i:s'),
        ];
        if ($this->method() == 'POST') {
            $data['user_id'] = auth()->user()->id;
        }

        $this->merge($data);
    }

    public function messages()
    {
        return [
            'customer_id.required_if' => __('Customer') . ' ' . __('is required'),            
        ];
    }

    public function attributes()
    {        
        $attributeNames['customer_id'] = __('Customer');

        return $attributeNames;
    }
}
