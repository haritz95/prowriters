<?php
namespace App\Models\Business;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class GrammaticalPerson extends Model
{

    protected $fillable = [
        'uuid',
        'slug',
        'name',
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
