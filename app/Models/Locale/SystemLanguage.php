<?php

namespace App\Models\Locale;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;

class SystemLanguage extends Model
{
    public $timestamps = false;

    const CACHE_AVAILABLE_LANGUAGES = 'available_languages';
    const CACHE_SYSTEM_LANGUAGES    = 'system_languages';
    const CACHE_ALLOWED_LANGUAGES   = 'allowed_languages';

    protected $fillable = [
        'iso_code',
        'name',
        'country_code',
        'layout_direction',
        'is_default',
    ];

    public static function forgetCache()
    {
        Cache::forget(SystemLanguage::CACHE_AVAILABLE_LANGUAGES);
        Cache::forget(SystemLanguage::CACHE_SYSTEM_LANGUAGES);
        Cache::forget(SystemLanguage::CACHE_ALLOWED_LANGUAGES);
    }

    public static function dropdowns()
    {
        return [
            'layout_directions' => [
                ['id' => 'ltr', 'name' => __('Left to right')],
                ['id' => 'rtl', 'name' => __('Right to left')],
            ],
        ];
    }
}
