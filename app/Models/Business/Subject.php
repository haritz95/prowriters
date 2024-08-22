<?php
namespace App\Models\Business;

use App\Models\Business\Service;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Subject extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'slug',
        'name',
        'percentage',
    ];

    public function services()
    {
        return $this->belongsToMany(Service::class);
    }

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
