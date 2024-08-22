<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Mews\Purifier\Facades\Purifier;
use Illuminate\Foundation\Http\FormRequest;

class StoreTaskMessageRequest extends FormRequest
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
        $this->merge([
            'message' => Purifier::clean($this->message),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'message' => 'required|min:3|max:2000',
            'files' => 'nullable|array',

        ];

        return $rules;
    }
}
