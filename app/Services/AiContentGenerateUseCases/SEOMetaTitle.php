<?php

namespace App\Services\AiContentGenerateUseCases;

use App\Contracts\AiContentGenerateUseCaseContract;

class SEOMetaTitle implements AiContentGenerateUseCaseContract
{

    public static function getUniqueIdentifier(): string
    {
        return 'seo_meta_title';
    }

    public static function validationRules(): array
    {
        return [
            'target_keywords' => 'required|max:300',
        ];
    }

    public static function prompt(): array
    {
        return [
            'prompt' => 'Generate SEO friendly meta title from target keywords in the :language_id language in a :tone_id tone. Target Keywords : :target_keywords',
        ];
    }

    public static function formFields(): array
    {
        return [
            [
                'type'        => 'textarea',
                'name'        => 'target_keywords',
                'label'       => __('Target keywords'),
                'placeholder' => __('app.ai_content.placeholders.seo_meta_title_keywords'),
                'rows'        => 3,
            ],
        ];
    }

    public static function dropdown(): array
    {
        return ['id' => self::getUniqueIdentifier(), 'name' => __('SEO Meta Title')];
    }

}
