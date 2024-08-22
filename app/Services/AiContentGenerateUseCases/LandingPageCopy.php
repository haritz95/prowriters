<?php

namespace App\Services\AiContentGenerateUseCases;

use App\Contracts\AiContentGenerateUseCaseContract;

class LandingPageCopy implements AiContentGenerateUseCaseContract
{

    public static function getUniqueIdentifier(): string
    {
        return 'landing_page_copy';
    }

    public static function validationRules(): array
    {
        return [
            'website_name'  => 'required|max:25',
            'about_website' => 'required|max:100',
            'features'      => 'required|max:300',
        ];
    }

    public static function prompt(): array
    {        
        return [
            'prompt' => 'Write a product description and feature sections in the :language_id language in a :tone_id tone for :website_name, :about_website . The website provides the following features : :features',
        ];
    }

    public static function formFields(): array
    {
        return [
            [
                'type'        => 'input',
                'name'        => 'website_name',
                'label'       => __('Website Name'),
                'placeholder' => get_company_name(),
            ],
            [
                'type'        => 'input',
                'name'        => 'about_website',
                'label'       => __('About Website'),
                'placeholder' => __('AI writer that generates content instantly'),
            ],
            [
                'type'        => 'textarea',
                'name'        => 'features',
                'label'       => __('Features'),
                'placeholder' => __('app.ai_content.placeholders.landing_page_copy_website_features'),
                'rows'        => 4,
            ],
        ];
    }

    public static function dropdown(): array
    {
        return ['id' => self::getUniqueIdentifier(), 'name' => __('Landing Page & Website Copies')];
    }

}
