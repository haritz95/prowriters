<?php

namespace App\Models\Website\Faq;

use Illuminate\Support\Facades\Cache;
use App\Models\Website\Faq\FaqCategory;
use Illuminate\Database\Eloquent\Model;

class FaqQuestion extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'locale',
        'title',
        'description',
        'show_on_home_page',
    ];

    public function categories()
    {
        return $this->belongsToMany(FaqCategory::class, 'tag_faq_categories', 'faq_question_id', 'faq_category_id');
    }

    public static function getCacheName($locale)
    {
        return $locale . '_faq_questions';
    }

    public static function clearCache()
    {
        Cache::forget(self::getCacheName(app()->getLocale()));
    }
}
