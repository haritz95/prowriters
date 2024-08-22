<?php

namespace App\Services\AiContentGenerateUseCases;

use App\Contracts\AiContentGenerateUseCaseContract;

class CoverLetter implements AiContentGenerateUseCaseContract
{

    public static function getUniqueIdentifier(): string
    {
        return 'cover_letter';
    }

    public static function validationRules(): array
    {
        return [
            'job_role' => 'required|max:75',
            'skills'   => 'required|max:100',
        ];
    }

    public static function prompt(): array
    {
        return [
            'prompt' => 'Write a cover letter for :job_role position in :language_id language using a :tone_id tone. Skills : :skills ',
        ];
    }

    public static function formFields(): array
    {
        return [
            [
                'type'        => 'input',
                'name'        => 'job_role',
                'label'       => __('Job Role'),
                'placeholder' => __('Digital Marketer'),
            ],
            [
                'type'        => 'input',
                'name'        => 'skills',
                'label'       => __('Skills'),
                'placeholder' => __('bog writing, SEO, social media, email'),
            ],
        ];
    }

    public static function dropdown(): array
    {
        return ['id' => self::getUniqueIdentifier(), 'name' => __('Cover Letter')];
    }

}
