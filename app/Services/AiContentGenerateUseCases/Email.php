<?php

namespace App\Services\AiContentGenerateUseCases;

use App\Contracts\AiContentGenerateUseCaseContract;

class Email implements AiContentGenerateUseCaseContract
{

    public static function getUniqueIdentifier(): string
    {
        return 'email';
    }

    public static function validationRules(): array
    {
        return [
            'key_points' => 'required|max:250',            
        ];
    }

    public static function prompt(): array
    {
        return [
            'prompt' => 'Write an email in :language_id language using a :tone_id tone. Key points : :key_points',
        ];
    }

    public static function formFields(): array
    {
        return [
            [
                'type'        => 'textarea',
                'name'        => 'key_points',
                'label'       => __('Key Points'),
                'placeholder' => __('Welcome! Are you enjoying the experience? Watch these tutorials & guides. Please reach out if any questions. Have a great day!'),
            ],            
        ];
    }

    public static function dropdown(): array
    {
        return ['id' => self::getUniqueIdentifier(), 'name' => __('Email')];
    }

}
