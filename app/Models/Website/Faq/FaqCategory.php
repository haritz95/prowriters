<?php

namespace App\Models\Website\Faq;

use App\Models\Website\Faq\FaqQuestion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class FaqCategory extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'locale',
        'name',
    ];

    public function questions()
    {
        return $this->belongsToMany(FaqQuestion::class, 'tag_faq_categories', 'faq_category_id', 'faq_question_id');
    }

    public static function getCacheName($locale)
    {
        return $locale . '_faq_categories';
    }

    public static function clearCache()
    {
        Cache::forget(self::getCacheName(app()->getLocale()));
    }
}
