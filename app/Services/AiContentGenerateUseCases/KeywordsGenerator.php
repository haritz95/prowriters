<?php

namespace App\Services\AiContentGenerateUseCases;

use App\Contracts\AiContentGenerateUseCaseContract;

class KeywordsGenerator implements AiContentGenerateUseCaseContract
{

    public static function getUniqueIdentifier(): string
    {
        return 'keyword_generator';
    }

    public static function validationRules(): array
    {
        return [
            'primary_keywords' => 'required|max:50',
        ];
    }

    public static function prompt(): array
    {
        return [
            'prompt' => 'Generate keywords and related phrases as comma-separated values for :primary_keywords in :language_id language using a :tone_id tone',
        ];
    }

    public static function formFields(): array
    {
        return [
            [
                'type'        => 'input',
                'name'        => 'primary_keywords',
                'label'       => __('Primary Keywords'),
                'placeholder' => __('AI writing assistant'),

            ],
        ];
    }

    public static function dropdown(): array
    {
        return ['id' => self::getUniqueIdentifier(), 'name' => __('Keyword Generator')];
    }

}
