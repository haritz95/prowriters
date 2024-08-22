<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAuthorLevelRequest extends FormRequest
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
            'name'          => 'required|string|max:192|unique:author_levels,name',
            'description'   => 'nullable|string|max:1024',
            'is_default'    => 'nullable|boolean',
            'is_popular'    => 'nullable|boolean',
            'numeric_value' => 'required|numeric|min:0',
            'percentage'    => config('app.validation_rules.percentage'),
            // 'number_of_tasks_at_a_time' => 'required|numeric',
        ];

        if ($this->method() == 'PATCH') {
            $rules['name'] = 'required|string|max:192|unique:author_levels,name,' . $this->authorLevel->id;
        }

        return $rules;
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            if ($this->method() == 'PATCH') {
                if ($this->authorLevel->is_default && $this->is_default != true) {
                    $validator->errors()->add('is_default', 'Please select a different level as default first');
                }
                if ($this->authorLevel->is_popular && $this->is_popular != true) {
                    $validator->errors()->add('is_popular', 'Popular can not be changed');
                }
            }

        });
    }

    public function attributes()
    {
        return [
            'name'          => __('Name'),
            'description'   => __('Description'),
            'is_default'    => __('Default Selection'),
            'is_popular'    => __('Is Popular'),
            'numeric_value' => __('Numeric Value'),
            // 'price_per_word_writing'      => __('Writing Price'),
            // 'price_per_word_editing'      => __('Editing Price'),
            // 'price_per_word_proofreading' => __('Proofreading Price'),
            // 'number_of_tasks_at_a_time'   => __('Number of tasks at a time'),

        ];
    }
}
