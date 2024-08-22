<?php

namespace App\Models\Website;

use App\Models\Website\WebsitePage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class WebsiteMenu extends Model
{
    const POSITION_TOP    = 'top';
    const POSITION_FOOTER = 'footer';

    protected $fillable = [
        'parent_id',
        'locale',
        'position',
        'name',
        'sequence_number',
        'website_page_id',
        'inactive',
    ];

    protected $casts = [
        'inactive' => 'boolean',
    ];

    //each menu might have one parent
    public function parent()
    {
        return $this->belongsToOne(static::class, 'parent_id');
    }

    //each menu might have multiple children
    public function children()
    {
        return $this->hasMany(static::class, 'parent_id')->orderBy('name', 'asc');
    }

    public function page()
    {
        return $this->belongsTo(WebsitePage::class, 'website_page_id', 'id')->select('id', 'slug');
    }

    public static function getCacheName($locale, $position)
    {
        return $locale . '_menu_' . $position;
    }

    public static function clearCache($position)
    {
        Cache::forget(self::getCacheName(app()->getLocale(), $position));
    }

    public static function getMenu($position, $locale = null)
    {
        if (is_null($locale)) {
            $locale = app()->getLocale();
        }
        return Cache::rememberForever(self::getCacheName($locale, $position), function () use ($locale, $position) {
            return self::with('page', 'children.page')
                ->whereNull('parent_id')
                ->where('locale', $locale)
                ->where('position', $position)
                ->get();
        });
    }

    public static function dropdowns($language)
    {
        return [
            'parent_menus' => [
                'top'    => self::
                    where('position', self::POSITION_TOP)
                    ->select(['id', 'name'])
                    ->where('locale', $language)
                    ->whereNull('parent_id')
                    ->get(),
                'footer' => self::
                    where('position', self::POSITION_FOOTER)
                    ->select(['id', 'name'])
                    ->where('locale', $language)
                    ->whereNull('parent_id')
                    ->get(),
            ],
            'positions'    => [
                ['id' => self::POSITION_TOP, 'name' => __('Top Navigation')],
                ['id' => self::POSITION_FOOTER, 'name' => __('Footer')],
            ],
            'custom_pages' => WebsitePage::
                where('type', WebsitePage::TYPE_CUSTOM)->select(['id', 'name'])
                ->where('locale', $language)
                ->get()->prepend(['id' => '', 'name' => __('None')]),
        ];
    }

}
