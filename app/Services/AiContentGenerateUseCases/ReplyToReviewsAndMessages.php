<?php

namespace App\Services\AiContentGenerateUseCases;

use App\Contracts\AiContentGenerateUseCaseContract;

class ReplyToReviewsAndMessages implements AiContentGenerateUseCaseContract
{

    public static function getUniqueIdentifier(): string
    {
        return 'reply_to_reviews_and_messages';
    }

    public static function validationRules(): array
    {
        return [
            'message' => 'required|max:300',
        ];
    }

    public static function prompt(): array
    {
        return [
            'prompt' => 'Write a response for the following message in the :language_id language in a :tone_id tone. Message : :message',
        ];
    }

    public static function formFields(): array
    {
        return [
            [
                'type'        => 'textarea',
                'name'        => 'message',
                'label'       => __('Message'),
                'placeholder' => __('app.ai_content.placeholders.reply_to_reviews_message', ['company_name' => get_company_name()]),
                'rows'        => 5,
            ],
        ];
    }

    public static function dropdown(): array
    {
        return ['id' => self::getUniqueIdentifier(), 'name' => __('Reply to Reviews & Messages')];
    }

}
