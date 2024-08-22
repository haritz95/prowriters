<?php

namespace App\Services\AiContentGenerateUseCases;

use App\Contracts\AiContentGenerateUseCaseContract;

class BusinessIdeas implements AiContentGenerateUseCaseContract
{

    public static function getUniqueIdentifier(): string
    {
        return 'business_ideas';
    }

    public static function validationRules(): array
    {
        return [
            'interest' => 'required|max:100',
            'skills' => 'required|max:100',
        ];
    }

    public static function prompt(): array
    {
        return [
            'prompt' => 'Generate a list of business ideas in :language_id language using a :tone_id tone. Interest : :interest , Skills : :skills ',
        ];
    }

    public static function formFields(): array
    {
        return [
            [
                'type'        => 'input',
                'name'        => 'interest',
                'label'       => __('Interest'),
                'placeholder' => __('Marketing SaaS'),
            ],     
            [
                'type'        => 'input',
                'name'        => 'skills',
                'label'       => __('Skills'),
                'placeholder' => __('copywriting, marketing, product development, AI'),
            ],     
        ];
    }

    public static function dropdown(): array
    {
        return ['id' => self::getUniqueIdentifier(), 'name' => __('Business Ideas')];
    }

}
