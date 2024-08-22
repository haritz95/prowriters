<?php

namespace App\Services\AiContentGenerateUseCases;

use App\Contracts\AiContentGenerateUseCaseContract;

class StoryPlot implements AiContentGenerateUseCaseContract
{

    public static function getUniqueIdentifier(): string
    {
        return 'story_plot';
    }

    public static function validationRules(): array
    {
        return [
            'story_idea' => 'required|max:200',
        ];
    }

    public static function prompt(): array
    {
        return [
            'prompt' => 'Generate creative plot outline based on story idea in the :language_id language in a :tone_id tone. Story Idea : :story_idea',
        ];
    }

    public static function formFields(): array
    {
        return [
            [
                'type'        => 'textarea',
                'name'        => 'story_idea',
                'label'       => __('Story idea'),
                'placeholder' => __('app.ai_content.placeholders.story_idea'),
                'rows'        => 3,
            ],
        ];
    }

    public static function dropdown(): array
    {
        return ['id' => self::getUniqueIdentifier(), 'name' => __('Story Plot')];
    }

}
