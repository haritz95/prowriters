<?php

namespace App\Models\ProjectManagement;

use App\Models\ProjectManagement\Task;
use Illuminate\Database\Eloquent\Model;

class TaskContent extends Model
{
    protected $fillable = [       
        'task_id',
        'title',
        'content',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id', 'id');
    }

}
