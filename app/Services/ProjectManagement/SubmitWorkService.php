<?php

namespace App\Services\ProjectManagement;

use App\Events\WorkSubmittedEvent;
use App\Events\WorkSubmittedForQAEvent;
use App\Models\ProjectManagement\SubmittedWork;
use App\Models\ProjectManagement\Task;
use App\Models\ProjectManagement\TaskStatus;
use App\Services\AttachmentService;

class SubmitWorkService
{

    public function create(Task $task, array $data)
    {
        $work = SubmittedWork::create([
            'task_id' => $task->id,
            'user_id' => auth()->user()->id,
            'message' => $data['message'],
        ]);

        // If Quality Assurance is turned on

        if (is_quality_control_enable()) {
            $task->task_status_id = TaskStatus::SUBMITTED_FOR_QA;
        } else {
            $task->task_status_id = TaskStatus::SUBMITTED_FOR_APPROVAL;
        }

        $task->save();

        (new AttachmentService($work, $data['files'], auth()->user()->id))->save();

        if (is_quality_control_enable()) {
            event(new WorkSubmittedForQAEvent($task));
        } else {
            event(new WorkSubmittedEvent($task));
        }
    }
}
