<?php

namespace App\Traits;

use App\Models\ProjectManagement\Task;

use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasTask
{
    
    public function task(): MorphOne
    {
        return $this->morphOne(Task::class, 'details');
    }
}
