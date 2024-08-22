<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWebsiteMenuRequest extends FormRequest
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
        $language = $this->language;

        switch ($this->method()) {
            case 'PATCH':
                $nameRule = 'required|unique:website_menus,name,' . $this->id;
                break;
            default:
                $nameRule = "required|unique:website_menus,name";
                break;
        }

        $rules = [
            'parent_id'       => 'nullable',
            'position'        => 'required',
            'name'            => $nameRule,
            'sequence_number' => 'nullable',
            'website_page_id' => 'nullable',
            'inactive'        => 'nullable|boolean',
        ];

        return $rules;
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'parent_id'       => __('Parent Menu'),
            'position'        => __('Position'),
            'name'            => __('Name'),
            'sequence_number' => __('Sequence Number'),
            'website_page_id' => __('Custom Page'),
            'inactive'        => __('Inactive'),
        ];
    }
}
