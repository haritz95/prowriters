<?php

namespace App\Traits;

use App\Models\Author\AuthorProfile;
use App\Models\ProjectManagement\Rating;
use App\Models\ProjectManagement\Task;
use App\Models\ProjectManagement\TaskStatus;

trait AuthorTrait
{
    
    public function authorProfile()
    {
        return $this->hasOne(AuthorProfile::class, 'user_id', 'id');
    }

    public function completedTasks()
    {
        return $this->hasMany(Task::class, 'author_id', 'id')->where('task_status_id', TaskStatus::COMPLETE);
    }

    public function authorRatings()
    {
        return $this->hasManyThrough(
            Rating::class,
            Task::class,
            'author_id', // Foreign key on the Task table...
            'task_id', // Foreign key on the Rating table...
            'id', // Local key on the users table...
            'id' // Local key on the Task table...
        );
    }

    // public function getAuthorAverageRatingAttribute()
    // {
    //     return $this->authorRatings()->average('value');
    // }
}
