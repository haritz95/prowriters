<?php

namespace App\Services\AiContentGenerateUseCases;

use App\Contracts\AiContentGenerateUseCaseContract;

class BusinessIdeaPitch implements AiContentGenerateUseCaseContract
{

    public static function getUniqueIdentifier(): string
    {
        return 'business_idea_pitch';
    }

    public static function validationRules(): array
    {
        return [
            'business_idea' => 'required|max:250',
        ];
    }

    public static function prompt(): array
    {
        return [
            'prompt' => 'Business idea pitch in :language_id language using a :tone_id tone. Business Idea : :business_idea',
        ];
    }

    public static function formFields(): array
    {
        return [
            [
                'type'        => 'textarea',
                'name'        => 'business_idea',
                'label'       => __('Business Idea'),
                'placeholder' => __('AI writing assistant for content writers and digital marketers'),

            ],     
        ];
    }

    public static function dropdown(): array
    {
        return ['id' => self::getUniqueIdentifier(), 'name' => __('Business Idea Pitch')];
    }

}
