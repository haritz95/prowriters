<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePostRequest extends FormRequest
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
                //$slugRule = 'required|alpha_dash|unique:posts,slug,' . $this->id;
                $slugRule = [
                    'required',
                    'alpha_dash',
                    Rule::unique('posts')->ignore($this->id)->where(function ($query) use ($language) {
                        return $query->where('locale', $language);
                    }),
                ];
                break;
            default:
                //$slugRule = "required|unique:posts,slug";
                $slugRule = [
                    'required',
                    'alpha_dash',
                    Rule::unique('posts')->where(function ($query) use ($language) {
                        return $query->where('locale', $language);
                    }),
                ];
                break;
        }

        $rules = [
            'slug'                      => $slugRule,
            'title'                     => 'required|max:191',
            'author_name'               => 'required|max:191',
            'thumbnail_image'           => 'required',
            'thumbnail_image_alt_title' => 'required|max:191',
            'cover_image'               => 'required',
            'cover_image_alt_title'     => 'required|max:191',
            'excerpt'                   => 'required',
            'content'                   => 'required',
            'meta_tags'                 => 'required',
            'published'                 => 'nullable|boolean',
            'disable_auto_slug_gen'     => 'nullable|boolean',
            'categories'                => 'nullable|array',

        ];

        return $rules;
    }
}
