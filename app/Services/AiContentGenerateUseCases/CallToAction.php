<?php

namespace App\Services\AiContentGenerateUseCases;

use App\Contracts\AiContentGenerateUseCaseContract;

class CallToAction implements AiContentGenerateUseCaseContract
{

    public static function getUniqueIdentifier(): string
    {
        return 'call_to_action';
    }

    public static function validationRules(): array
    {
        return [
            'description' => 'required|max:250',
        ];
    }

    public static function prompt(): array
    {
        return [
            'prompt' => 'Write an effective Call to Action in :language_id language using a :tone_id tone. Description : :description',
        ];
    }

    public static function formFields(): array
    {
        return [
            [
                'type'        => 'textarea',
                'name'        => 'description',
                'label'       => __('Description'),
                'placeholder' => __('An AI writing assistant that helps you automatically generate content for anything - from emails & blogs to ads & social media, We can create original, engaging copies for you within seconds, at a fraction of the cost.'),
                'rows'        => 4,
            ],
        ];
    }

    public static function dropdown(): array
    {
        return ['id' => self::getUniqueIdentifier(), 'name' => __('Call To Action')];
    }

}
