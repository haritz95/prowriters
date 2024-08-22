<?php

namespace App\Services\AiContentGenerateUseCases;

use App\Contracts\AiContentGenerateUseCaseContract;

class SocialMediaPost implements AiContentGenerateUseCaseContract
{

    public static function getUniqueIdentifier(): string
    {
        return 'social_media_post';
    }

    public static function validationRules(): array
    {
        return [
            'product_name' => 'required|max:25',            
            'product_description' => 'required|max:300',            
        ];
    }

    public static function prompt(): array
    {
        return [
            'prompt' => 'Write an Ad Copy for social media post in :language_id language using a :tone_id tone. Product Name : :product_name , Product Description :product_description',
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
                'placeholder' => __('Prowriters') . ' '. __('is an AI-powered writing tool that helps you create high-quality content, in just a few seconds, at a fraction of the cost!'),
            ],            
        ];
    }

    public static function dropdown(): array
    {
        return ['id' => self::getUniqueIdentifier(), 'name' => __('Facebook, Twitter, LinkedIn Ads')];
    }

}
