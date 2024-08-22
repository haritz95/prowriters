<?php

namespace App\Models\Locale;

use Illuminate\Database\Eloquent\Model;

class SystemTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'system_text_id',
        'locale',
        'translated_text',
    ];
}
