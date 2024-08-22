<?php

namespace App\Http\Controllers\Author;

use App\Events\WorkSubmittedEvent;
use App\Events\WorkSubmittedForQAEvent;
use App\Http\Controllers\Controller;
use App\Models\ProjectManagement\SubmittedWork;
use App\Models\ProjectManagement\Task;
use App\Models\ProjectManagement\TaskStatus;
use App\Services\AttachmentService;
use App\Services\ProjectManagement\TaskMessageService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SubmitWorkController extends Controller
{

    public function index(Request $request, Task $task)
    {
        $task->load('status');

        $withQuery = [
            'attachments'                 => function ($q) {
                $q->select(['id', 'uuid', 'name', 'display_name', 'attachable_id', 'attachable_type']);
            },
            'user'                        => function ($q) {
                $q->select(['id', 'uuid', 'type', 'code', 'photo']);
            },
            'revisionRequest',
            'revisionRequest.user'        => function ($q) {
                $q->select(['id', 'uuid', 'type', 'code', 'photo']);
            },
            'revisionRequest.attachments' => function ($q) {
                $q->select(['id', 'uuid', 'name', 'display_name', 'attachable_id', 'attachable_type']);
            },
        ];

        $qualityAssuranceQuery = [
            'qualityAssurance',
            'qualityAssurance.user'        => function ($q) {
                $q->select(['id', 'uuid', 'type', 'code', 'photo']);
            },
            'qualityAssurance.attachments' => function ($q) {
                $q->select(['id', 'uuid', 'name', 'display_name', 'attachable_id', 'attachable_type']);
            },
        ];
        $withQuery = $withQuery + $qualityAssuranceQuery;

        return inertia('Author/Tasks/Works/Index', [
            'tab'   => 'works',
            'task'  => $task,
            'works' => $task->submittedWorks()->with($withQuery)->orderBy('id', 'DESC')->get(),
            'data'  => [
                'urls'                            => [
                    'submit_work' => route('author.tasks.works.create', $task->uuid),
                ],
                'statuses_allows_submitting_work' => [TaskStatus::IN_PROGRESS],
            ],
        ]);
    }

    public function create(TaskMessageService $taskMessageService, Task $task)
    {
        if ($task->task_status_id != TaskStatus::IN_PROGRESS) {
            return redirect()->route('author.tasks.index');
        }

        $task->load('status');

        return Inertia::modal('Author/Tasks/Works/Create', [

            'task' => $task,
            'data' => [
                'title'  => __('Submit Work'),
                'config' => $taskMessageService->getConfigForCreateMessage(route('author.tasks.works.store', $task->uuid)),

            ],
        ])->baseRoute('author.tasks.works.index', $task->uuid);
    }

    public function store(Request $request, Task $task)
    {
        if ($task->task_status_id != TaskStatus::IN_PROGRESS) {
            return redirect()->route('author.tasks.index');
        }

        $request->validate([
            'message' => 'required|min:3|max:2000',
            'files'   => 'required|array',
        ]);

        $work          = new SubmittedWork();
        $work->message = $request->message;
        $work->user_id = auth()->user()->id;
        $work->task_id = $task->id;
        $work->save();

        // If Quality Assurance is turned on
        
        if(!($task->bidRequest) && is_quality_control_enable())
        {
            $task->task_status_id = TaskStatus::SUBMITTED_FOR_QA;
        }
        else {
            $task->task_status_id = TaskStatus::SUBMITTED_FOR_APPROVAL;
        }
    
        $task->save();

        (new AttachmentService($work, $request->input('files'), auth()->user()->id))->save();

        if (is_quality_control_enable()) {
            event(new WorkSubmittedForQAEvent($task));
        } else {
            event(new WorkSubmittedEvent($task));
        }

        return redirect()->route('author.tasks.works.index', $task->uuid)->withSuccess(__('Successfully submitted'));
    }
}
