<?php

namespace App\Services\AiContentGenerateUseCases;

use App\Contracts\AiContentGenerateUseCaseContract;

class CopywritingFrameworkAida implements AiContentGenerateUseCaseContract
{

    public static function getUniqueIdentifier(): string
    {
        return 'copywriting_framework_aida';
    }

    public static function validationRules(): array
    {
        return [
            'description' => 'required|max:300',
        ];
    }

    public static function prompt(): array
    {
        return [
            'prompt' => 'Generate a marketing copy in AIDA format in :language_id language using a :tone_id tone. Description : :description',
        ];
    }

    public static function formFields(): array
    {
        return [
            [
                'type'        => 'textarea',
                'name'        => 'description',
                'label'       => __('Product or Brand Description'),
                'placeholder' => get_company_name() . ' ' . __('is an AI-powered writing tool that helps you create high-quality content, in just a few seconds, at a fraction of the cost'),
                'rows'        => 4,
            ],
        ];
    }

    public static function dropdown(): array
    {
        return ['id' => self::getUniqueIdentifier(), 'name' => __('Copywriting Framework AIDA')];
    }

}
