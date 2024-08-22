<?php

namespace App\Services\AiContentGenerateUseCases;

use App\Contracts\AiContentGenerateUseCaseContract;

class BlogIdea implements AiContentGenerateUseCaseContract
{

    public static function getUniqueIdentifier(): string
    {
        return 'blog_idea';
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
            'prompt' => 'Write a blog post topic and outline in :language_id language using a :tone_id tone that is related to the following keywords: :keywords',
        ];
    }

    public static function formFields(): array
    {
        return [
            [
                'type'        => 'input',
                'name'        => 'keywords',
                'label'       => __('Keywords'),
                'placeholder' => __('AI Writing Assistant'),
                
            ],
        ];
    }

    public static function dropdown(): array
    {
        return ['id' => self::getUniqueIdentifier(), 'name' => __('Blog Idea & Outline')];
    }

    // pr(\Illuminate\Support\Str::snake(class_basename($this::class)));
}
