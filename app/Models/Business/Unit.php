<?php
namespace App\Models\Business;

use Illuminate\Support\Str;
use App\Models\Business\Urgency;
use App\Models\Business\Assignment;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'quantity',
        'assignment_id',
        'urgency_id',
        'price',
    ];

    public function assignments()
    {
        return $this->belongsTo(Assignment::class);
    }
    
    public function urgency()
    {
        return $this->belongsTo(Urgency::class);
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
    }
}
