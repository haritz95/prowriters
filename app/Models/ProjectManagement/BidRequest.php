<?php

namespace App\Models\ProjectManagement;

use App\Models\NumberGenerator;
use App\Models\ProjectManagement\Task;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BidRequest extends Model
{
    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }

    protected $fillable = [
        'uuid',
        'number',
        'task_id',
        'budget',
        'bid_request_status_id',
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid   = Str::orderedUuid();
            $model->number = NumberGenerator::gen(self::class);
        });
    }

    public function status()
    {
        return $this->belongsTo(BidRequestStatus::class, 'bid_request_status_id', 'id');
    }

    public function task()
    {
        return $this->hasOne(Task::class, 'id', 'task_id');
    }

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    public static function sortingOptions()
    {
        return [
            ['id' => 'budget_high_to_low', 'name' => __('Budget High to Low')],
            ['id' => 'budget_low_to_high', 'name' => __('Budget Low to High')],
            ['id' => '', 'name' => __('Latest')],
        ];
    }

}
