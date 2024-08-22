<?php

namespace App\Traits;

use App\Models\Comment;

trait HasComment
{

    function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
