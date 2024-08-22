<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreWebsitePageRequest extends FormRequest
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
                // $nameRule = 'required|unique:website_pages,name,' . $this->id
                $nameRule = [
                    'required',                   
                    Rule::unique('website_pages')->ignore($this->id)->where(function ($query) use ($language) {
                        return $query->where('locale', $language);
                    }),
                ];
                $slugRule = [
                    'required',
                    'alpha_dash',
                    Rule::unique('website_pages')->ignore($this->id)->where(function ($query) use ($language) {
                        return $query->where('locale', $language);
                    }),
                ];
                break;
            default:
                $nameRule = "required|unique:website_pages,name";
                $slugRule = [
                    'required',
                    'alpha_dash',
                    Rule::unique('website_pages')->where(function ($query) use ($language) {
                        return $query->where('locale', $language);
                    }),
                ];
                break;
        }
       
        $rules = [
            'disable_auto_slug_gen'            => 'nullable|boolean',
            'name'                             => $nameRule,
            'title'                            => 'required|max:192',
            'slug'                             => $slugRule,
            'sub_title'                        => 'required|max:192',
            'image'                            => 'nullable',
            'image_position'                   => 'required_with:image',
            'image_alt_text'                   => 'required_with:image|max:192',
            'content'                          => 'required',
            'meta_tags'                        => 'required',
            'appearance'                       => 'required|array',
            'appearance.bg_color'              => 'nullable',
            'appearance.text_color'            => 'nullable',
            'appearance.title_alignment'       => 'required',
            'appearance.image_alignment'       => 'required_with:image',
            'appearance.header_minimum_height' => 'required|numeric|integer',

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
            'meta_tags'                        => __('Meta Tags'),
            'appearance'                       => __('Appearance'),
            'appearance.bg_color'              => __('Background Color'),
            'appearance.text_color'            => __('Text Color'),
            'appearance.title_alignment'       => __('Title Alignment'),
            'appearance.image_alignment'       => __('Image Alignment'),
            'appearance.header_minimum_height' => __('Header Minimum Height'),
        ];
    }
}
