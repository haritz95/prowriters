<?php

namespace App\Traits;

use App\Models\Attachment;

trait HasAttachment
{

    function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }
}
