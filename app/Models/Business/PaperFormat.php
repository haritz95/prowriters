<?php

namespace App\Models\Business;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PaperFormat extends Model
{

    protected $fillable = [
        'uuid',
        'slug',
        'name',
    ];

    protected $casts = [
        'inactive' => 'boolean',
    ];

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
