<?php

namespace App\Services\AiContentGenerateUseCases;

use App\Contracts\AiContentGenerateUseCaseContract;

class QuestionAnswer implements AiContentGenerateUseCaseContract
{

    public static function getUniqueIdentifier(): string
    {
        return 'question_answer';
    }

    public static function validationRules(): array
    {
        return [
            'topic_description' => 'required|max:250',
        ];
    }

    public static function prompt(): array
    {
        return [
            'prompt' => 'Generate a list of questions and its answers in the :language_id language in a :tone_id tone. Question Topic : :topic_description',
        ];
    }

    public static function formFields(): array
    {
        return [
            [
                'type'        => 'textarea',
                'name'        => 'topic_description',
                'label'       => __('Topic Description'),
                'placeholder' => __('app.ai_content.placeholders.question_answer_topic_description'),
                'rows'        => 4,
            ],
        ];
    }

    public static function dropdown(): array
    {
        return ['id' => self::getUniqueIdentifier(), 'name' => __('Question & Answer')];
    }

}
