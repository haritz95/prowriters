<?php

namespace App\Models\Website;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class WebsitePage extends Model
{
    const TYPE_SYSTEM = 'system';
    const TYPE_CUSTOM = 'custom';

    const HOME               = 'home';
    const BLOG               = 'blog';
    const FAQ                = 'faq';
    const LOGIN              = 'login';
    const REGISTRATION       = 'registration';
    const FORGOT_PASSWORD    = 'forgot_password';
    const AUTHOR_APPLICATION = 'author_application';
    const CONTACT            = 'contact';

    protected $fillable = [
        'locale',
        'type',
        'slug',
        'disable_auto_slug_gen',
        'name',
        'title',
        'sub_title',
        'content',
        'image',
        'image_alt_text',
        'image_position',
        'appearance',
        'additional_data',
        'meta_tags',
        'published',
        'user_id',
    ];

    protected $casts = [
        'appearance'            => 'array',
        'additional_data'       => 'array',
        'disable_auto_slug_gen' => 'boolean',
    ];

    public static function getCacheName($locale, $page)
    {
        return $locale . '_pages_' . $page;
    }

    public static function clearCache($page)
    {
        Cache::forget(self::getCacheName(app()->getLocale(), $page));
    }

    public static function getImagePositions()
    {
        return [
            ['id' => 'left', 'name' => __('Left')],
            ['id' => 'right', 'name' => __('Right')],
            ['id' => 'center', 'name' => __('Center')],
        ];
    }

    public static function get($page, $locale = null)
    {
        if (is_null($locale)) {
            $locale = app()->getLocale();
        }
        return Cache::rememberForever(self::getCacheName($locale, $page), function () use ($locale, $page) {
            return self::where('name', $page)->where('locale', $locale)->get()->first();
        });
    }

    public static function getCustomPage($slug, $locale = null)
    {
        if (is_null($locale)) {
            $locale = app()->getLocale();
        }

        $cache_name = self::getCacheName(app()->getLocale(), $slug);
        $pages      = Cache::rememberForever($cache_name, function () {
            return WebsitePage::all();
        });

        if ($pages->count() > 0) {
            return $pages->where('slug', $slug)->first();
        }
        return null;
    }

}
