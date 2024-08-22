<?php

namespace App\Services\AiContentGenerateUseCases;

use App\Contracts\AiContentGenerateUseCaseContract;

class InterviewQuestions implements AiContentGenerateUseCaseContract
{

    public static function getUniqueIdentifier(): string
    {
        return 'interview_questions';
    }

    public static function validationRules(): array
    {
        return [
            'interviewee_bio'   => 'required|max:250',
            'interview_context' => 'required|max:250',
        ];
    }

    public static function prompt(): array
    {
        return [
            'prompt' => 'Create a list of 10 questions for an interview in :language_id language using a :tone_id tone. About interviewee : :interviewee_bio . Interview Content : :interview_context',
        ];
    }

    public static function formFields(): array
    {
        return [
            [
                'type'        => 'textarea',
                'name'        => 'interviewee_bio',
                'label'       => __('Interviewee Bio'),
                'placeholder' => __('John Doe is an experienced product manager with a track record of leading many successful products at well-known startups'),
            ],
            [
                'type'        => 'textarea',
                'name'        => 'interview_context',
                'label'       => __('Interview context'),
                'placeholder' => __('Interviewing a candidate for the role of senior product manager'),
            ],
        ];
    }

    public static function dropdown(): array
    {
        return ['id' => self::getUniqueIdentifier(), 'name' => __('Interview Questions')];
    }

}
