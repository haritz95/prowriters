<?php

namespace App\Services\AiContentGenerateUseCases;

use App\Contracts\AiContentGenerateUseCaseContract;

class SEOMetaDescription implements AiContentGenerateUseCaseContract
{

    public static function getUniqueIdentifier(): string
    {
        return 'seo_meta_description';
    }

    public static function validationRules(): array
    {
        return [
            'page_meta_title' => 'required|max:300',
        ];
    }

    public static function prompt(): array
    {
        return [
            'prompt' => 'Generate SEO friendly meta description from meta title in the :language_id language in a :tone_id tone. Meta Title : :page_meta_title',
        ];
    }

    public static function formFields(): array
    {
        return [
            [
                'type'        => 'textarea',
                'name'        => 'page_meta_title',
                'label'       => __('Page Meta Title'),
                'placeholder' => __('app.ai_content.placeholders.seo_meta_description_page_meta_title', ['company_name' => get_company_name()]),
                'rows'        => 3,
            ],
        ];
    }

    public static function dropdown(): array
    {
        return ['id' => self::getUniqueIdentifier(), 'name' => __('SEO Meta Description')];
    }

}
