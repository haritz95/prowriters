<?php

namespace App\Models\ProjectManagement;

use App\Models\ProjectManagement\QualityAssurance;
use App\Models\ProjectManagement\RevisionRequest;
use App\Models\ProjectManagement\Task;
use App\Models\User;
use App\Traits\HasAttachment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SubmittedWork extends Model
{

    use HasAttachment;

    protected $fillable = [
        'uuid',       
        'task_id',
        'user_id',
        'message',
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = (string) Str::orderedUuid();
        });
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id', 'id');
    }

    public function revisionRequest()
    {
        return $this->hasOne(RevisionRequest::class, 'submitted_work_id', 'id');
    }

    public function qualityAssurance()
    {
        return $this->hasOne(QualityAssurance::class, 'submitted_work_id', 'id');
    }
}
