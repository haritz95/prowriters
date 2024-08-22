<?php

namespace App\Services\AiContentGenerateUseCases;

use App\Contracts\AiContentGenerateUseCaseContract;

class BrandName implements AiContentGenerateUseCaseContract
{

    public static function getUniqueIdentifier(): string
    {
        return 'brand_name';
    }

    public static function validationRules(): array
    {
        return [
            'brand_description' => 'required|max:200',
        ];
    }

    public static function prompt(): array
    {
        return [
            'prompt' => 'A list of brand names in :language_id language using a :tone_id tone. Brand Description: :brand_description',
        ];
    }

    public static function formFields(): array
    {
        return [
            [
                'type'        => 'textarea',
                'name'        => 'brand_description',
                'label'       => __('Brand Description'),
                'placeholder' => __('An AI writing tool for auto-generating content and copies for blogs, social media, and more'),

            ],     
        ];
    }

    public static function dropdown(): array
    {
        return ['id' => self::getUniqueIdentifier(), 'name' => __('Brand Name')];
    }

}
