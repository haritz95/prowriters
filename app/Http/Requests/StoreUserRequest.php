<?php

namespace App\Http\Requests;

use App\Enums\PermissionType;
use App\Rules\PhoneNumber;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
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
            'first_name'              => 'required|max:255',
            'last_name'               => 'required|max:255',
            'email'                   => 'required|email|unique:users',
            'phone'                   => ['nullable', new PhoneNumber],
            'country_code'            => 'required',
            'timezone'                => 'required',
            'inactive'                => 'nullable|boolean',
            'send_notification_email' => 'nullable|boolean',
        ];

        switch ($this->method()) {
            case 'PATCH':
                $rules['email'] = 'required|email|unique:users,uuid,' . $this->user->uuid;

                break;
            default:
                $rules['password'] = 'required';
                $rules['role']     = ['required', Rule::in(PermissionType::getRoles())];
                break;
        }
        return $rules;
    }
}
