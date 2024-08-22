<?php

namespace App\Models\Ai;

use App\Services\AiContentGenerateUseCases\UseCaseConfiguration;
use Illuminate\Database\Eloquent\Model;

class AiContent extends Model
{
    protected $fillable = [
        'uuid',
        'user_id',
        'title',
        'content', 
    ];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public static function dropdown()
    {
        $useCaseConfiguration = new UseCaseConfiguration();

        return [
            'fields'    => $useCaseConfiguration->getAllFormFields(),
            'languages' => [
                ['id' => 'English', 'name' => __('🇺🇸 English')],
                ['id' => 'Arabic', 'name' => __('🇦🇪 Arabic')],
                // ['id' => 'English', 'name' => __('🇧🇩 Bangla')],
                ['id' => 'English', 'name' => __('🇧🇬 Bulgarian')],
                ['id' => 'English', 'name' => __('🇨🇳 Chinese (S)')],
                
                ['id' => 'Arabic', 'name' => __('🇨🇳 Chinese (S)')],
                ['id' => 'English', 'name' => __('🇨🇿 Czech')],
                ['id' => 'English', 'name' => __('🇩🇰 Danish')],
             
                ['id' => 'Arabic', 'name' => __('🇳🇱 Dutch')],
                ['id' => 'English', 'name' => __('🇮🇷 Farsi')],
                ['id' => 'English', 'name' => __('🇵🇭 Filipino')],
                
                ['id' => 'Arabic', 'name' => __('🇫🇮 Finnish')],
                ['id' => 'English', 'name' => __('🇫🇷 French')],
                ['id' => 'English', 'name' => __('🇩🇪 German')],
                
                ['id' => 'Arabic', 'name' => __('🇬🇷 Greek')],
                ['id' => 'English', 'name' => __('🇮🇱 Hebrew')],
                ['id' => 'English', 'name' => __('🇮🇳 Hindi')],
                
                ['id' => 'Arabic', 'name' => __('🇭🇺 Hungarian')],
                ['id' => 'English', 'name' => __('🇮🇩 Indonesian')],
                ['id' => 'English', 'name' => __('🇮🇹 Italian')],
                
                ['id' => 'Arabic', 'name' => __('🇯🇵 Japanese')],
                ['id' => 'English', 'name' => __('🇰🇷 Korean')],
                ['id' => 'English', 'name' => __('🇱🇻 Latvian')],
                
                ['id' => 'Arabic', 'name' => __('🇱🇹 Lithuanian')],
                ['id' => 'English', 'name' => __('🇲🇾 Malay')],
                ['id' => 'English', 'name' => __('🇳🇴 Norwegian')],
               
                ['id' => 'Arabic', 'name' => __('🇵🇱 Polish')],
                ['id' => 'English', 'name' => __('🇵🇹 Portuguese')],
                ['id' => 'English', 'name' => __('🇷🇴 Romanian')],
                
                ['id' => 'Arabic', 'name' => __('🇷🇺 Russian')],
                ['id' => 'English', 'name' => __('🇸🇰 Slovak')],
                ['id' => 'English', 'name' => __('🇸🇮 Slovenian')],
               
                ['id' => 'Arabic', 'name' => __('🇪🇸 Spanish')],
                ['id' => 'English', 'name' => __('🇸🇪 Swedish')],
                ['id' => 'English', 'name' => __('🇹🇭 Thai')],
                
                ['id' => 'Arabic', 'name' => __('🇹🇷 Turkish')],
                ['id' => 'English', 'name' => __('🇺🇦 Ukrainian')],
                ['id' => 'English', 'name' => __('🇻🇳 Vietnamese')],
            ],
            'use_cases' => $useCaseConfiguration->getUseCasesDropdown(),
            'tones'     => [
                ['id' => 'Appreciative', 'name' => __('Appreciative')],
                ['id' => 'Assertive', 'name' => __('Assertive')],
                ['id' => 'Awestruck', 'name' => __('Awestruck')],
                ['id' => 'Candid', 'name' => __('Candid')],

                ['id' => 'Casual', 'name' => __('Casual')],
                ['id' => 'Cautionary', 'name' => __('Cautionary')],
                ['id' => 'Compassionate', 'name' => __('Compassionate')],

                ['id' => 'Convincing', 'name' => __('Convincing')],
                ['id' => 'Critical', 'name' => __('Critical')],
                ['id' => 'Earnest', 'name' => __('Earnest')],

                ['id' => 'Enthusiastic', 'name' => __('Enthusiastic')],
                ['id' => 'Formal', 'name' => __('Formal')],
                ['id' => 'Funny', 'name' => __('Funny')],

                ['id' => 'Humble', 'name' => __('Humble')],
                ['id' => 'Humorous', 'name' => __('Humorous')],
                ['id' => 'Informative', 'name' => __('Informative')],

                ['id' => 'Inspirational', 'name' => __('Inspirational')],
                ['id' => 'Joyful', 'name' => __('Joyful')],
                ['id' => 'Passionate', 'name' => __('Passionate')],
                ['id' => 'Thoughtful', 'name' => __('Thoughtful')],
                ['id' => 'Urgent', 'name' => __('Urgent')],
                ['id' => 'Worried', 'name' => __('Worried')],
            ],
        ];
    }
}
