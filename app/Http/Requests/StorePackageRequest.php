<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePackageRequest extends FormRequest
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
                $nameRule = [
                    'required',
                    Rule::unique('assignments')->ignore($this->package->id),
                ];
                break;
            default:
                $nameRule = "required|max:192|unique:assignments";
                break;
        }

        return [
            'name'                  => $nameRule,
            'price'                 => 'required',
            'urgency_id'            => 'required',
            'author_level_id'       => 'required',
            'author_payment_amount' => 'required',
            'description'           => 'required|max:192',
            'deliverables'          => 'required|max:3000',
        ];
    }

    public function attributes()
    {
        return [
            'name'                  => __('Name'),
            'price'                 => __('Price'),
            'urgency_id'            => __('Turnaround Time'),
            'description'           => __('Description'),
            'deliverables'          => __('Deliverables'),
            'author_level_id'       => __('Author Level'),
            'author_payment_amount' => __('Author Payment Amount'),

        ];
    }

}
