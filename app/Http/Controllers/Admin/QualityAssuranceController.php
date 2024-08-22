<?php

namespace App\Http\Controllers\Admin;

use App\Events\QAApprovedEvent;
use App\Events\QARejectedEvent;
use App\Http\Controllers\Controller;
use App\Models\ProjectManagement\QualityAssurance;
use App\Models\ProjectManagement\Task;
use App\Models\ProjectManagement\TaskStatus;
use App\Services\AttachmentService;
use App\Services\ProjectManagement\TaskMessageService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class QualityAssuranceController extends Controller
{
    private $taskMessageService;

    public function __construct(TaskMessageService $taskMessageService)
    {
        $this->taskMessageService = $taskMessageService;
    }

    public function index(Task $task)
    {
        if ($task->task_status_id == TaskStatus::SUBMITTED_FOR_QA) {
            $task->load([
                'status',
                'submittedWorks'             => function ($q) {
                    $q->latest('created_at')->first();
                },
                'submittedWorks.attachments' => function ($q) {
                    $q->select(['id', 'uuid', 'name', 'display_name', 'attachable_id', 'attachable_type']);
                },
                'submittedWorks.user'        => function ($q) {
                    $q->select(['id', 'uuid', 'type', 'photo']);
                },
            ]);
        } else {
            $task->load('status');
        }

        return inertia('Admin/Tasks/QualityAssurances/Index', [
            'tab'  => 'qa',
            'task' => $task,
            'data' => [
                'config' => $this->taskMessageService->getConfigForCreateMessage(route('admin.tasks.qa.store', $task->uuid)),

            ],
        ]);
    }

    public function create(Task $task)
    {
        if ($task->task_status_id != TaskStatus::SUBMITTED_FOR_QA) {
        }

        return Inertia::modal('Admin/Tasks/QualityAssurances/Create', [
            'data' => [
                'title'  => __('Quality Assurance'),
                'config' => $this->taskMessageService->getConfigForCreateMessage(route('admin.tasks.qa.store', $task->uuid)),
            ],
        ])->baseRoute('admin.tasks.qa.index', $task->uuid);
    }

    public function store(Request $request, Task $task)
    {
        $request->validate([
            'feedback' => 'required|in:approve,reject',
            'message'  => 'required|max:5000',
            'files'    => 'nullable|array',
        ]);

        $submittedWork = $task->submittedWorks()->latest('id')->get()->first();

        // Record the QA Report
        $qa                    = new QualityAssurance();
        $qa->message           = $request->message;
        $qa->user_id           = auth()->user()->id;
        $qa->submitted_work_id = $submittedWork->id;
        $qa->save();

        // Update the task status
        if ($request->feedback == 'reject') {
            $task->task_status_id = TaskStatus::QA_REJECTED;
        } else {
            $task->task_status_id = TaskStatus::SUBMITTED_FOR_APPROVAL;
        }
        $task->save();

        // Save the attachment
        (new AttachmentService($qa, $request->input('files'), auth()->user()->id))->save();

        // Dispatching event
        if ($request->feedback == 'reject') {
            event(new QARejectedEvent($task));
        } else {
            event(new QAApprovedEvent($task));
        }
        return redirect()->route('admin.tasks.show', $task->uuid)->withSuccess(__('Successfully submitted'));
    }

    public function destroy(Task $task, QualityAssurance $qa)
    {
        return $this->removeItem(function () use ($qa) {
            $qa->delete();
        }, ['admin.tasks.works.index', $task->uuid]);
    }
}
