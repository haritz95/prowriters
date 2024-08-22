<?php

namespace App\Http\Requests;

use App\Rules\PhoneNumber;
use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
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
        $rules = [
            'first_name'         => 'required|max:255',
            'last_name'          => 'required|max:255',
            'email'              => 'required|email|unique:users',
            'phone'              => ['nullable', new PhoneNumber],
            'country_code'       => 'required',
            'timezone'           => 'required',
            'language'           => 'nullable',
            'inactive'           => 'nullable|boolean',
            'internal_note'      => 'nullable|max:500',
            'allow_paying_later' => 'boolean',
            // 'credit_limit'       => 'required_if:allow_credit,1|numeric',

        ];

        switch ($this->method()) {
            case 'PATCH':
                $rules['email'] = 'required|email|unique:users,uuid,' . $this->user->uuid;

                break;
            default:
                $rules['password'] = 'required';
                break;
        }
        return $rules;
    }
}
