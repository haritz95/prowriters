<?php

namespace App\Models\ProjectManagement;

use App\Models\ProjectManagement\Task;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id'
    ];

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
        'task_id',
        'user_id',
        'number',
        'comment'
    ];

    function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    function task()
    {
        return $this->belongsTo(Task::class);
    }
}
