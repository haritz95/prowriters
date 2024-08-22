<?php

namespace App\Services;

use App\Models\Attachment;
use Illuminate\Support\Str;

class AttachmentService
{

    private $model;
    private $attachments;
    private $userId;

    function __construct($model, array $attachments, $userId = null)
    {
        $this->model = $model;
        $this->attachments = $attachments;
        $this->userId = $userId;
    }

    public function save()
    {
        if ($this->model && $this->attachments && is_array($this->attachments) && count($this->attachments) > 0) {
            foreach ($this->attachments as $row) {
                if (isset($row['name'])) {
                    $attachment = new Attachment();
                    $attachment->uuid = Str::orderedUuid();
                    $attachment->name = $row['name'];
                    $attachment->display_name = $row['display_name'];
                    $attachment->user_id = $this->userId;
                    $this->model->attachments()->save($attachment);
                }
            }
        }
    }
}
