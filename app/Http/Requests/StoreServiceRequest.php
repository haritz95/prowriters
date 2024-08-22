<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use App\Rules\CommaSeparatedFileExtensions;
use Illuminate\Foundation\Http\FormRequest;

class StoreServiceRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name'                              => "required|max:192|unique:services",
            'description'                       => 'required',
            'assignment_label'                  => 'required',
            'image'                             => 'required',
            'unit_name'                         => 'required',
            'minimum_order_quantity'            => 'required|integer',
            'maximum_file_size'                 => 'required|integer',
            'maximum_number_of_files_to_upload' => 'required|integer',
            'allowed_file_extensions'           => ['required', new CommaSeparatedFileExtensions],
            'inactive'                          => 'nullable|boolean',
            'not_available_for_bidding'         => 'nullable|boolean',
            'not_available_for_direct_order'    => 'nullable|boolean',
            'commission'                        => 'nullable',
            'commission_from_bid'               => 'nullable',
        ];

        switch ($this->method()) {
            case 'PATCH':
                $rules['name'] = [
                    'required',
                    Rule::unique('services')->ignore($this->service->id),
                ];
                break;
            default:
                $rules['service_type_id'] = 'required';
                break;
        }

        return $rules;
    }

    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key = null, $default = null);

        return array_merge($validated, [
            'inactive'                  => ($validated['inactive']) ? true : null,
            'not_available_for_bidding' => ($validated['not_available_for_bidding']) ? true : null,
        ]);

    }

    public function attributes()
    {
        return [
            'name'                => __('Name'),
            'service_type_id'     => __('Service Type'),
            'commission_from_bid' => __('Commission from bid'),

            // 'service_id'            => __('Service'),
            // 'description'           => __('Description'),
            // 'deliverables'          => __('Deliverables'),
            // 'author_level_id'       => __('Author Level'),
            // 'author_payment_amount' => __('Author Payment Amount'),
            // 'urgency_id'            => __('Turnaround Time'),

        ];
    }

}
