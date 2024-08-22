<?php

namespace App\Models\Business;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'percentage',
        // 'is_default_for_content_writing',
        // 'available_for_content_writing',
        // 'available_for_translation',
    ];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = (string) Str::orderedUuid();
            $model->slug = Str::of($model->name)->slug('-');
        });
        self::updating(function ($model) {           
            $model->slug = Str::of($model->name)->slug('-');
        });
    }
}
