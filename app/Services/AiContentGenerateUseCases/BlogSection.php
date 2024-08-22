<?php

namespace App\Services\AiContentGenerateUseCases;

use App\Contracts\AiContentGenerateUseCaseContract;

class BlogSection implements AiContentGenerateUseCaseContract
{

    public static function getUniqueIdentifier(): string
    {
        return 'blog_section';
    }

    public static function validationRules(): array
    {
        return [
            'section_topic' => 'required|max:100',
            'section_keywords' => 'required|max:125',
        ];
    }

    public static function prompt(): array
    {
        return [
            'prompt' => 'Write a blog post on :section_topic in :language_id language using a :tone_id tone that is related to the following keywords: :section_keywords',
        ];
    }

    public static function formFields(): array
    {
        return [
            [
                'type'        => 'textarea',
                'name'        => 'section_topic',
                'label'       => __('Section Topic'),
                'placeholder' => __('Role of AI Writers in the future of copywriting'),
                
            ],
            [
                'type'        => 'textarea',
                'name'        => 'section_keywords',
                'label'       => __('Section Keywords'),
                'placeholder' => __('ai writer, blog generator, best writing software'),                
            ],
        ];
    }

    public static function dropdown(): array
    {
        return ['id' => self::getUniqueIdentifier(), 'name' => __('Blog Section')];
    }

 
}
