<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginOrRegisterRequest extends FormRequest
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
        if (!auth()->check()) {

            $rules = [
                'customer_type' => 'required|in:returning_customer,new_customer',
            ];

            if ($this->customer_type == 'new_customer') {
                $rules['email']                 = 'required|string|email|max:255|unique:users';
                $rules['password']              = 'required|string|min:6|confirmed';
                $rules['password_confirmation'] = 'required';
                $rules['phone']                 = 'nullable';
                $rules['first_name']            = 'required_if:customer_type,new_customer|string|max:192';
                $rules['last_name']             = 'required_with:first_name|string|max:192';
            } else {
                $rules['email']    = 'required|string|email|max:255';
                $rules['password'] = 'required';
            }

            return $rules;
        }
        return [];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    // public function attributes()
    // {
    //     $attributeNames['files_data'] = __('Attachment');

    //     return $attributeNames;
    // }
}
