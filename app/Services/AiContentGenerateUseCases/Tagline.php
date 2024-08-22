<?php

namespace App\Services\AiContentGenerateUseCases;

use App\Contracts\AiContentGenerateUseCaseContract;

class Tagline implements AiContentGenerateUseCaseContract
{

    public static function getUniqueIdentifier(): string
    {
        return 'tagline';
    }

    public static function validationRules(): array
    {
        return [
            'description' => 'required|max:200',
        ];
    }

    public static function prompt(): array
    {
        return [
            'prompt' => 'Write short tagline in the :language_id language in a :tone_id tone from the description. Description : :description',
        ];
    }

    public static function formFields(): array
    {
        return [
            [
                'type'        => 'textarea',
                'name'        => 'description',
                'label'       => __('Description'),
                'placeholder' => __('app.ai_content.placeholders.tagline_description', ['company_name' => get_company_name()]),
                'rows'        => 3,
            ],
        ];
    }

    public static function dropdown(): array
    {
        return ['id' => self::getUniqueIdentifier(), 'name' => __('Tagline & Headline')];
    }

}
