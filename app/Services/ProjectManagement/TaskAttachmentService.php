<?php

namespace App\Services\ProjectManagement;

use App\Models\ProjectManagement\Task;
use App\Http\Requests\StoreTaskAttachmentRequest;



class TaskAttachmentService
{
    public function getConfigForCreateAttachment($form_submit_url, $upload_attachment_url = NULL)
    {
        return [
            'default_message' => '',
            'allowed_file_extensions' => '.jpg,.png,.gif, .doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.pdf,.zip,.rar',
            'maximum_number_of_files_to_upload' => 5,
            'maximum_file_size' => 100,
            'existing_files' => null,
            'urls' => [
                'upload_attachment'   => ($upload_attachment_url) ? $upload_attachment_url : route('attachments.store'),
                'submit_form' => $form_submit_url,
            ],
        ];
    }

    public function store(Task $task, StoreTaskAttachmentRequest $request)
    {
        (app()->makeWith('App\Services\AttachmentService', [
            'model' => $task,
            'attachments' => $request->attachments,
            'userId' => auth()->user()->id
        ]))->save();
    }
}
