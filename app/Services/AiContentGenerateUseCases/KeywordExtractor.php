<?php

namespace App\Services\AiContentGenerateUseCases;

use App\Contracts\AiContentGenerateUseCaseContract;

class KeywordExtractor implements AiContentGenerateUseCaseContract
{

    public static function getUniqueIdentifier(): string
    {
        return 'keyword_extractor';
    }

    public static function validationRules(): array
    {
        return [
            'text' => 'required|max:1000',
        ];
    }

    public static function prompt(): array
    {
        return [
            'prompt' => 'Extract key word from text and show it in :language_id language using a :tone_id tone. Text : :text',
        ];
    }

    public static function formFields(): array
    {
        return [
            [
                'type'        => 'textarea',
                'name'        => 'text',
                'label'       => __('Text'),
                'placeholder' => __('Artificial intelligence is a boon for copywriters. They can now use AI writing assistants to write content for websites and blogs. These assistants generate articles, descriptions, and other text-based content.'),
                'rows'        => 7,
            ],
        ];
    }

    public static function dropdown(): array
    {
        return ['id' => self::getUniqueIdentifier(), 'name' => __('Keyword Extractor')];
    }

}
