<?php

namespace App\Models\Business;

use App\Models\Business\Service;
use App\Models\Business\Unit;
use App\Models\Business\Urgency;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'name',
        'description',
        'deliverables',
        'price',
        'unit_name',
        'author_level_id',
        'author_payment_amount',
        'urgency_id',
        'number_of_revisions_allowed',
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

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function units()
    {
        return $this->hasMany(Unit::class);
    }

    public function urgency()
    {
        return $this->belongsTo(Urgency::class);
    }


}
