<?php

namespace App\Models\Website;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class WebsiteTestimonial extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'locale',
        'avatar',
        'name',
        'position',
        'rating',
        'comment',
    ];

    public static function getCacheName($locale)
    {
        return $locale . '_testimonials';
    }

    public static function clearCache()
    {
        Cache::forget(self::getCacheName(app()->getLocale()));
    }

    public static function ratings()
    {
        return [
            ['id' => 1, 'name' => 1],
            ['id' => 2, 'name' => 2],
            ['id' => 3, 'name' => 3],
            ['id' => 4, 'name' => 4],
            ['id' => 5, 'name' => 5],
        ];
    }

    public static function get()
    {
        return Cache::rememberForever(self::getCacheName(app()->getLocale()), function () {
            return self::where('locale', app()->getLocale())->get();
        });
    }
}
