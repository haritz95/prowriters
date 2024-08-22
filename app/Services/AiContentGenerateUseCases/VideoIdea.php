<?php

namespace App\Services\AiContentGenerateUseCases;

use App\Contracts\AiContentGenerateUseCaseContract;

class VideoIdea implements AiContentGenerateUseCaseContract
{

    public static function getUniqueIdentifier(): string
    {
        return 'video_idea';
    }

    public static function validationRules(): array
    {
        return [
            'keywords' => 'required|max:100',
        ];
    }

    public static function prompt(): array
    {
        return [
            'prompt' => 'Generate a list of videos from the following keywords in the :language_id language in a :tone_id tone. Keywords : :keywords',
        ];
    }

    public static function formFields(): array
    {
        return [
            [
                'type'        => 'input',
                'name'        => 'keywords',
                'label'       => __('Keywords'),
                'placeholder' => __('app.ai_content.placeholders.vide_idea_keywords'),
            ],
        ];
    }

    public static function dropdown(): array
    {
        return ['id' => self::getUniqueIdentifier(), 'name' => __('Video Idea')];
    }

}
