<?php

namespace App\Services\AiContentGenerateUseCases;

use App\Contracts\AiContentGenerateUseCaseContract;

class VideoDescription implements AiContentGenerateUseCaseContract
{

    public static function getUniqueIdentifier(): string
    {
        return 'video_description';
    }

    public static function validationRules(): array
    {
        return [
            'video_title' => 'required|max:100',
        ];
    }

    public static function prompt(): array
    {
        return [
            'prompt' => 'Generate a description for a video in the :language_id language in a :tone_id tone from its title. Video Title : :video_title',
        ];
    }

    public static function formFields(): array
    {
        return [
            [
                'type'        => 'textarea',
                'name'        => 'video_title',
                'label'       => __('Video Title'),
                'placeholder' => __('app.ai_content.placeholders.video_description_video_title'),
                'rows'        => 3,
            ],
        ];
    }

    public static function dropdown(): array
    {
        return ['id' => self::getUniqueIdentifier(), 'name' => __('Video Description')];
    }

}
