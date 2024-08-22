<?php

namespace App\Models\Website\Blog;

use App\Models\Website\Blog\PostCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Post extends Model
{
    protected $fillable = [
        'locale',
        'slug',
        'title',
        'author_name',
        'thumbnail_image',
        'thumbnail_image_alt_title',
        'cover_image',
        'cover_image_alt_title',
        'excerpt',
        'content',
        'meta_tags',
        'published',
        'disable_auto_slug_gen',
        'user_id',
    ];

    protected $casts = [
        'published'             => 'boolean',
        'disable_auto_slug_gen' => 'boolean',
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::created(function ($post) {
            self::clearCachePostPagination($post->locale);
        });

        static::updated(function ($post) {
            self::clearCache($post->locale, $post->slug);
            self::clearCachePostPagination($post->locale);
        });

        static::deleted(function ($post) {
            self::clearCache($post->locale, $post->slug);
            self::clearCachePostPagination($post->locale);
        });
    }

    public function categories()
    {
        return $this->belongsToMany(PostCategory::class, 'post_category_tag');
    }

    public function excerpt()
    {
        return Str::limit(strip_tags($this->excerpt), 80);
    }

    public static function getCacheName($locale, $name = null)
    {
        return $locale . '_blog_posts_' . $name;
    }

    public static function clearCache($locale, $post_slug)
    {
        Cache::forget(self::getCacheName($locale, $post_slug));
    }

    public static function clearCachePostPagination($locale)
    {
        $posts           = self::get($locale);
        $number_of_pages = ceil($posts->total() / $posts->perPage());
        if ($number_of_pages > 0) {
            for ($page = 1; $page <= $number_of_pages; $page++) {

                $cache_name = self::getCacheName($locale . '_' . $page);

                if (Cache::has($cache_name)) {
                    Cache::forget($cache_name);
                }

            }
        }

    }

    public static function get($locale = null)
    {
        if (is_null($locale)) {
            $locale = app()->getLocale();
        }
        // example : en_1
        $name = $locale . '_' . request()->get('page', 1);

        return Cache::rememberForever(self::getCacheName($name), function () use ($locale) {
            return self::with('categories')
                ->where('locale', $locale)
                ->orderBy('created_at', 'DESC')
                ->where('published', true)
                ->paginate(12);
        });
    }

    public static function getBySlug($slug, $locale = null)
    {
        if (is_null($locale)) {
            $locale = app()->getLocale();
        }
        // example : en_blog-post-title
        $name = $locale . '_' . $slug;

        return Cache::rememberForever(self::getCacheName($name), function () use ($locale, $slug) {
            return self::with(['categories'])
                ->where('locale', $locale)
                ->where('slug', $slug)
                ->orderBy('created_at', 'DESC')
                ->where('published', true)
                ->get()->first();
        });
    }
}
