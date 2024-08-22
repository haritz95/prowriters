<?php

namespace App\Models\Business;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AuthorLevel extends Model
{
    protected $fillable = [
        'uuid',
        'slug',
        'name',
        'description',
        'is_popular',
        'is_default',
        'numeric_value',
        'percentage',     
     
    ];

    protected $casts = [
        'is_popular' => 'boolean',
        'is_default' => 'boolean',
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

    public static function dropdowns()
    {
        $data['numeric_values'] = [
            ['id' => 1, 'name' => 1],
            ['id' => 2, 'name' => 2],
            ['id' => 3, 'name' => 3],
            ['id' => 4, 'name' => 4],
            ['id' => 5, 'name' => 5],
        ];

        return $data;
    }

}
