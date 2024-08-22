<?php

namespace App\Services\AiContentGenerateUseCases;

use App\Contracts\AiContentGenerateUseCaseContract;

class TestimonialAndReview implements AiContentGenerateUseCaseContract
{

    public static function getUniqueIdentifier(): string
    {
        return 'testimonial_and_review';
    }

    public static function validationRules(): array
    {
        return [
            'name'         => 'required|max:50',
            'review_title' => 'required|max:150',
        ];
    }

    public static function prompt(): array
    {
        return [
            'prompt' => 'Generate review in the :language_id language in a :tone_id tone from the review title. Item name : :name, Review Title : :review_title',
        ];
    }

    public static function formFields(): array
    {
        return [
            [
                'type'        => 'input',
                'name'        => 'name',
                'label'       => __('Name'),
                'placeholder' => get_company_name(),
            ],
            [
                'type'        => 'textarea',
                'name'        => 'review_title',
                'label'       => __('Review Title'),
                'placeholder' => __('app.ai_content.placeholders.review_title'),
                'rows'        => 3,
            ],
        ];
    }

    public static function dropdown(): array
    {
        return ['id' => self::getUniqueIdentifier(), 'name' => __('Testimonial & Review')];
    }

}
