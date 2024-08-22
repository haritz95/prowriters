<?php

namespace App\Services\AiContentGenerateUseCases;

use App\Contracts\AiContentGenerateUseCaseContract;

class JobDescription implements AiContentGenerateUseCaseContract
{

    public static function getUniqueIdentifier(): string
    {
        return 'job_description';
    }

    public static function validationRules(): array
    {
        return [
            'job_role' => 'required|max:50',
        ];
    }

    public static function prompt(): array
    {
        return [
            'prompt' => 'Create a job description in :language_id language using a :tone_id tone. Job Role : :job_role',
        ];
    }

    public static function formFields(): array
    {
        return [
            [
                'type'        => 'input',
                'name'        => 'job_role',
                'label'       => __('Job role'),
                'placeholder' => __('Product Manager'),
            ],
        ];
    }

    public static function dropdown(): array
    {
        return ['id' => self::getUniqueIdentifier(), 'name' => __('Job Description')];
    }

}
