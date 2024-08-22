<?php

namespace App\Models\Website\Blog;

use App\Models\Website\Blog\Post;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class PostCategory extends Model
{
    protected $fillable = [
        'locale',
        'slug',
        'name',
        'meta_tags',
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::created(function ($category) {
            self::clearCache($category->locale);
        });

        static::updated(function ($category) {
            self::clearCache($category->locale);

        });

        static::deleted(function ($category) {
            self::clearCache($category->locale);
        });
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug(strtolower($value), '-');
    }

    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_category_tag');
    }

    public static function getCacheName($locale)
    {
        return $locale . '_blog_categories';
    }

    public static function clearCache($locale)
    {
        Cache::forget(self::getCacheName($locale));
    }

    public static function get($locale = null)
    {
        if (is_null($locale)) {
            $locale = app()->getLocale();
        }

        return Cache::rememberForever(self::getCacheName($locale), function () use ($locale) {
            return self::where('locale', $locale)->get();
        });
    }

    public static function getBySlug($slug, $locale = null)
    {
        if (is_null($locale)) {
            $locale = app()->getLocale();
        }
        $categories = self::get($locale);

        if ($categories->count() > 0) {
            return $categories->where('slug', $slug)->first();
        }
        return null;

    }

    public static function dropdown($locale = null)
    {
        if (is_null($locale)) {
            $locale = app()->getLocale();
        }
        $categories = self::get($locale);

        $options[''] = __('Choose Category');

        foreach ($categories as $category) {
            $options[$category->slug] = $category->name;
        }

        return $options;
    }
}
