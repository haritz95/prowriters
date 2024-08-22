<?php

namespace App\Models\Website;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class HomePageSection extends Model
{
    public $timestamps = false;

    const CACHE_HOME_PAGE_SECTIONS = 'homepage_sections';

    const WHY_CHOOSE_US = 'why_choose_us';
    const HOW_IT_WORKS  = 'how_it_works';
    const ABOUT_US      = 'about_us';
    const HERO          = 'hero';
    const FOOTER        = 'footer';

    protected $fillable = [
        'locale',
        'name',
        'title',
        'sub_title',
        'content',
        'image',
        'image_alt_text',
        'image_position',
        'appearance',
        'additional_data',
    ];

    protected $casts = [
        'appearance'      => 'array',
        'additional_data' => 'array',
    ];

    public static function getCacheName($locale)
    {
        return $locale . '_homepage_sections';
    }

    public static function clearCache($locale)
    {
        Cache::forget(self::getCacheName($locale));
    }

    public static function getImagePositions()
    {
        return [
            ['id' => 'left', 'name' => __('Left')],
            ['id' => 'right', 'name' => __('Right')],
            ['id' => 'center', 'name' => __('Center')],
        ];
    }

    public static function get($section_name)
    {
        return Cache::rememberForever(self::getCacheName(app()->getLocale()), function () {
            return self::where('locale', app()->getLocale())->get();
        })->where('name', $section_name)->first();    
    }
}
