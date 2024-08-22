<?php

namespace App\Models\Business;

use App\Models\Business\Service;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AdditionalService extends Model
{
    protected $fillable = [
        'type',
        'name',
        'description',
        'per_entered_quantity_label',
        'price',
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

    public function services()
    {
        return $this->belongsToMany(Service::class, 'service_additional_service');
    }
}
