<?php

namespace App\Services\AiContentGenerateUseCases;

use App\Contracts\AiContentGenerateUseCaseContract;

class GoogleSearchAds implements AiContentGenerateUseCaseContract
{

    public static function getUniqueIdentifier(): string
    {
        return 'google_search_ads';
    }

    public static function validationRules(): array
    {
        return [
            'product_name'        => 'required|max:25',
            'product_description' => 'required|max:300',
            'target_keywords'     => 'required|max:25',
        ];
    }

    public static function prompt(): array
    {
        return [
            'prompt' => 'Write an Ad Copy for Google Search Ads in :language_id language using a :tone_id tone. Product Name : :product_name , Product Description :product_description, Target Keywords : :target_keywords',
        ];
    }

    public static function formFields(): array
    {
        return [
            [
                'type'        => 'input',
                'name'        => 'product_name',
                'label'       => __('Product Name'),
                'placeholder' => __('Prowriters'),
            ],
            [
                'type'        => 'textarea',
                'name'        => 'product_description',
                'label'       => __('Product description'),
                'placeholder' => __('Prowriters') . ' ' . __('is an AI-powered writing tool that helps you create high-quality content, in just a few seconds, at a fraction of the cost!'),
            ],
            [
                'type'        => 'input',
                'name'        => 'target_keywords',
                'label'       => __('Target Keywords'),
                'placeholder' => __('Content writing'),
            ],
        ];
    }

    public static function dropdown(): array
    {
        return ['id' => self::getUniqueIdentifier(), 'name' => __('Google Search Ads')];
    }

}
