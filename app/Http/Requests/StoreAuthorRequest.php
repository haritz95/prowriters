<?php

namespace App\Http\Requests;

use App\Rules\PhoneNumber;
use Illuminate\Foundation\Http\FormRequest;

class StoreAuthorRequest extends FormRequest
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
            'first_name'             => 'required|string|max:255',
            'last_name'              => 'required|string|max:255',
            'email'                  => "required|string|email|max:255|unique:users,email",
            'phone'                  => ['nullable', new PhoneNumber],
            'country_code'           => 'required',
            'timezone'               => 'required',
            'author_level_id'    => 'required',
            'education_level_id'     => 'required',
            'bio'                    => 'nullable|string|min:100|max:3000',
            'address'                => 'nullable|string|max:400',
            'state'                  => 'nullable|string|max:192',
            'city'                   => 'nullable|string|max:192',
            'payment_method'         => 'required|string|max:100',
            'payment_method_details' => 'required|string|max:100',
            'blog_url'               => 'nullable',
            'online_portfolio_url'   => 'nullable',
            'linked_in_url'          => 'nullable',
            'years_of_experience'    => 'required|integer',
            'service_id_1'           => 'required',
            'service_id_2'           => 'nullable',
            'service_id_3'           => 'nullable',
            'subject_id_1'           => 'nullable',
            'subject_id_2'           => 'nullable',
            'subject_id_3'           => 'nullable',
            'subject_id_4'           => 'nullable',
            'subject_id_5'           => 'nullable',
            'inactive'               => 'nullable|boolean',

        ];
        switch ($this->method()) {
            case 'PATCH':
                $rules['email'] = 'required|string|email|max:255|unique:users,email,' . $this->user->id;
                break;
            default:
                $rules['send_notification_email'] = 'nullable|boolean';
                $rules['password']                = 'required';
                break;
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'education_level_id'           => __('Education Level'),
            'years_of_experience'          => __('Years of Experience'),
            'number_of_clients'            => __('Number of Clients'),
            'number_of_projects_completed' => __('Number of Projects Completed'),
            'subject_id_1'                 => __('Subject 1'),
            'subject_id_2'                 => __('Subject 2'),
            'subject_id_3'                 => __('Subject 3'),
            'subject_id_4'                 => __('Subject 4'),
            'subject_id_5'                 => __('Subject 5'),

            'service_id_1' => __('Service 1'),
            'service_id_2' => __('Service 2'),

            'blog_url'               => __('Blog URL'),
            'online_portfolio_url'   => __('Online Portfolio URL'),
            'linked_in_url'          => __('LinkedIn URL'),
            'payment_method'         => __('Payment Method'),
            'payment_method_details' => __('Payment Method Details'),

        ];
    }
}
