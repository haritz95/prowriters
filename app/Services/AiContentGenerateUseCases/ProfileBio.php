<?php

namespace App\Services\AiContentGenerateUseCases;

use App\Contracts\AiContentGenerateUseCaseContract;

class ProfileBio implements AiContentGenerateUseCaseContract
{

    public static function getUniqueIdentifier(): string
    {
        return 'profile_bio';
    }

    public static function validationRules(): array
    {
        return [
            'about_you' => 'required|max:250',
        ];
    }

    public static function prompt(): array
    {
        return [
            'prompt' => 'Write a profile bio in the :language_id language in a :tone_id tone. Profile Information : :about_you',
        ];
    }

    public static function formFields(): array
    {
        return [
            [
                'type'        => 'textarea',
                'name'        => 'about_you',
                'label'       => __('About You'),
                'placeholder' => __('app.ai_content.placeholders.about_you'),
                'rows'        => 4,
            ],
        ];
    }

    public static function dropdown(): array
    {
        return ['id' => self::getUniqueIdentifier(), 'name' => __('Profile Bio')];
    }

}
