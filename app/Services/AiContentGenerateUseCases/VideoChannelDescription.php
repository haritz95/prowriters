<?php

namespace App\Services\AiContentGenerateUseCases;

use App\Contracts\AiContentGenerateUseCaseContract;

class VideoChannelDescription implements AiContentGenerateUseCaseContract
{

    public static function getUniqueIdentifier(): string
    {
        return 'video_channel_description';
    }

    public static function validationRules(): array
    {
        return [
            'channel_purpose' => 'required|max:150',
        ];
    }

    public static function prompt(): array
    {
        return [
            'prompt' => 'Generate a description for a video channel in the :language_id language in a :tone_id tone from its purpose. Purpose : :channel_purpose',
        ];
    }

    public static function formFields(): array
    {
        return [
            [
                'type'        => 'textarea',
                'name'        => 'channel_purpose',
                'label'       => __('Channel purpose'),
                'placeholder' => __('app.ai_content.placeholders.video_channel_description_channel_purpose'),
                'rows'        => 3,
            ],
        ];
    }

    public static function dropdown(): array
    {
        return ['id' => self::getUniqueIdentifier(), 'name' => __('Video Channel Description')];
    }

}
