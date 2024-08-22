<?php

namespace App\Http\Requests;

use App\Enums\ServiceType;
use App\Rules\MoneyFormat;
use Illuminate\Foundation\Http\FormRequest;
use Mews\Purifier\Facades\Purifier;

class StoreBidRequest extends FormRequest
{
    private $instruction_char_limit = 'max:' . 20000;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'title'       => Purifier::clean($this->title),
            'instruction' => Purifier::clean($this->instruction),
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
            'budget' => ['required', new MoneyFormat],

        ];

        switch ($this->service->service_type_id) {
            case ServiceType::ACADEMIC_WRITING:
                $rules = $rules + $this->academicWritingFields();
                break;
            case ServiceType::CONTENT_WRITING:
                $rules = $rules + $this->contentWritingFields();
                break;
            case ServiceType::FIXED_PRICE:
                $rules = $rules + $this->fixedPriceFields();
                break;

            default:
                # code...
                break;
        }

        return $rules;
    }

    public function academicWritingFields()
    {
        $rules = [
            'assignment_id'     => 'required',
            'number_of_words'   => 'required|numeric',
            'subject_id'        => 'required',
            'academic_level_id' => 'required',
            'paper_format_id'   => 'required',
            'number_of_sources' => 'nullable|numeric',
            'title'             => 'required|max:255',
            'instruction'       => 'required|' . $this->instruction_char_limit,
            'attachments'       => 'nullable|array',
            'author_level_id'   => 'nullable',
        ];

        return $rules;
    }

    public function fixedPriceFields()
    {
        $rules = [
            'assignment_id'   => 'required',
            'title'           => 'required|max:255',
            'instruction'     => 'nullable|' . $this->instruction_char_limit,
            'attachments'     => 'required|array',
            'author_level_id' => 'nullable',
        ];

        return $rules;
    }

    public function contentWritingFields()
    {
        $rules = [
            'assignment_id'                         => 'required',
            'number_of_words'                       => 'required|numeric',
            'subject_id'                            => 'required',
            'language_id'                           => 'required',
            'author_level_id'                       => 'nullable',
            'added_additional_services'             => 'nullable|array',
            'title'                                 => 'required|max:255',
            'content_goals'                         => 'required|' . $this->instruction_char_limit,
            'grammatical_person_id'                 => 'nullable',
            'target_audience'                       => 'nullable|' . $this->instruction_char_limit,
            'target_keywords'                       => 'nullable|' . $this->instruction_char_limit,
            'links_to_example_content'              => 'nullable|' . $this->instruction_char_limit,
            'style_and_tone'                        => 'nullable|' . $this->instruction_char_limit,
            'structure_and_formatting_requirements' => 'nullable|' . $this->instruction_char_limit,
            'referencing_and_linking_preferences'   => 'nullable|' . $this->instruction_char_limit,
            'things_to_avoid'                       => 'nullable|' . $this->instruction_char_limit,
            'additional_notes'                      => 'nullable|' . $this->instruction_char_limit,
            'attachments'                           => 'nullable|array',
        ];

        return $rules;
    }

    // protected function failedValidation(Validator $validator)
    // {
    //     throw new HttpResponseException(response()->json([
    //         'errors' => $validator->errors(),
    //         'status' => false
    //     ]));
    // }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        $attributeNames['attachments'] = __('Attachment');
        $attributeNames['budget']      = __('Budget');

        return $attributeNames;
    }

    // public function messages()
    // {
    //     return [
    //         'bid_budget.required_if' => __('validation.required'),
    //     ];
    // }
}
