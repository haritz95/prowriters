<?php

namespace App\Services\AiContentGenerateUseCases;

use App\Contracts\AiContentGenerateUseCaseContract;

class SongLyrics implements AiContentGenerateUseCaseContract
{

    public static function getUniqueIdentifier(): string
    {
        return 'song_lyrics';
    }

    public static function validationRules(): array
    {
        return [
            'song_idea' => 'required|max:200',
        ];
    }

    public static function prompt(): array
    {
        return [
            'prompt' => 'Generate short lyrics based on a song idea in the :language_id language in a :tone_id tone. Song Idea : :song_idea',
        ];
    }

    public static function formFields(): array
    {
        return [
            [
                'type'        => 'textarea',
                'name'        => 'song_idea',
                'label'       => __('Song idea'),
                'placeholder' => __('app.ai_content.placeholders.song_idea'),
                'rows'        => 3,
            ],
        ];
    }

    public static function dropdown(): array
    {
        return ['id' => self::getUniqueIdentifier(), 'name' => __('Song Lyrics')];
    }

}
