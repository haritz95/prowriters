<?php

namespace App\Models\ProjectManagement;

use App\Models\ProjectManagement\Task;
use App\Traits\HasAttachment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TaskMessage extends Model
{
    use HasAttachment;

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = (string) Str::orderedUuid();
        });
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id', 'id');
    }
}
