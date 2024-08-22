<?php

namespace App\Services\AiContentGenerateUseCases;

use App\Contracts\AiContentGenerateUseCaseContract;

class PostAndCaptionIdeas implements AiContentGenerateUseCaseContract
{

    public static function getUniqueIdentifier(): string
    {
        return 'post_and_caption_ideas';
    }

    public static function validationRules(): array
    {
        return [
            'post_topic' => 'required|max:100',
        ];
    }

    public static function prompt(): array
    {
        return [
            'prompt' => 'Generate post and caption idea for social media in the :language_id language in a :tone_id tone. Post Topic : :post_topic',
        ];
    }

    public static function formFields(): array
    {
        return [
            [
                'type'        => 'textarea',
                'name'        => 'post_topic',
                'label'       => __('Post Topic'),
                'placeholder' => __('Inspiring community members to share their voices and ideas openly'),
            ],
        ];
    }

    public static function dropdown(): array
    {
        return ['id' => self::getUniqueIdentifier(), 'name' => __('Post & Caption Ideas')];
    }

}
