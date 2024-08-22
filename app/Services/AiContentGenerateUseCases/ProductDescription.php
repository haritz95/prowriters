<?php

namespace App\Services\AiContentGenerateUseCases;

use App\Contracts\AiContentGenerateUseCaseContract;

class ProductDescription implements AiContentGenerateUseCaseContract
{

    public static function getUniqueIdentifier(): string
    {
        return 'product_description';
    }

    public static function validationRules(): array
    {
        return [
            'product_name'  => 'required|max:25',
            'about_product' => 'required|max:500',
        ];
    }

    public static function prompt(): array
    {
        return [
            'prompt' => 'Write a product description in the :language_id language in a :tone_id tone. Product Name: :product_name, About Product : :about_product',
        ];
    }

    public static function formFields(): array
    {
        return [
            [
                'type'        => 'input',
                'name'        => 'product_name',
                'label'       => __('Product Name'),
                'placeholder' => get_company_name(),
            ],
            [
                'type'        => 'textarea',
                'name'        => 'about_product',
                'label'       => __('About Product'),
                'placeholder' => __('app.ai_content.placeholders.about_product', ['company_name' => get_company_name()]),
                'rows'        => 4,
            ],
        ];
    }

    public static function dropdown(): array
    {
        return ['id' => self::getUniqueIdentifier(), 'name' => __('Product Description')];
    }

}
