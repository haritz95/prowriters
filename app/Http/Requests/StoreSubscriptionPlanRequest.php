<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSubscriptionPlanRequest extends FormRequest
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
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if (isset($this->price) && $this->price && $this->price == '0.000000') {
            $this->merge([
                'price' => 0,
            ]);
        }

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
                $rules = [
                    'stripe_id' => [
                        Rule::requiredIf(fn() => !$this->is_free),
                        Rule::unique('subscription_plans')->ignore($this->subscriptionPlan->id),

                    ],
                    'title' => 'required|max:192|unique:subscription_plans',
                ];
                break;
            default:
                $rules = [
                    'stripe_id' => [
                        Rule::requiredIf(fn() => !$this->is_free),
                        'unique:subscription_plans',
                    ],
                    'title'     => 'required|max:192|unique:subscription_plans',
                ];
                break;
        }

        return array_merge([
            'is_free'                                => 'nullable',
            'description'                            => 'nullable|max:2000',
            'price'                                  => [
                Rule::requiredIf(fn() => !$this->is_free),
                'nullable',
                'numeric',
                'min:0',
            ],
            'number_of_characters_allowed_per_month' => 'required|numeric|min:1',
        ], $rules);
    }

    public function attributes()
    {
        return [
            'is_free'                                => __('Is Free'),
            'description'                            => __('Description'),
            'price'                                  => __('Price'),
            'number_of_characters_allowed_per_month' => __('Number of characters allowed per month'),
            'stripe_id'                              => __('Stripe ID'),
            'title'                                  => __('Title'),

        ];
    }

    public function messages()
    {
        return [
            'stripe_id.required_if' => __('validation.required'),
            'stripe_id.unique'      => __('validation.unique'),
        ];
    }
}
