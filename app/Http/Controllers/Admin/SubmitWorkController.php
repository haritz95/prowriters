<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProjectManagement\SubmittedWork;
use App\Models\ProjectManagement\Task;
use App\Models\ProjectManagement\TaskStatus;
use App\Services\ProjectManagement\SubmitWorkService;
use App\Services\ProjectManagement\TaskMessageService;
use Illuminate\Http\Request;

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

        return inertia('Admin/Tasks/Works/Index', [
            'tab'   => 'works',
            'task'  => $task,
            'works' => $task->submittedWorks()->with($withQuery)->orderBy('id', 'DESC')->get(),
            'data'  => [],
        ]);
    }

    public function create(TaskMessageService $taskMessageService, Task $task)
    {
        // if ($task->task_status_id != TaskStatus::IN_PROGRESS) {
        //     return redirect()->route('admin.tasks.index');
        // }
        return inertia()->modal('Admin/Tasks/Works/Create', [
            'task' => $task->load('status'),
            'data' => [
                'title'  => __('Submit Work'),
                'config' => $taskMessageService->getConfigForCreateMessage(route('admin.tasks.works.store', $task->uuid)),

            ],
        ])->baseRoute('admin.tasks.works.index', $task->uuid);
    }

    public function store(Request $request, Task $task, SubmitWorkService $submitWorkService)
    {
        $data = $request->validate([
            'message' => 'required|min:3|max:2000',
            'files'   => 'required|array',
        ]);

        $submitWorkService->create($task, $data);

        return redirect()->route('admin.tasks.works.index', $task->uuid)->withSuccess(__('Successfully submitted'));
    }

    public function destroy(Task $task, SubmittedWork $work)
    {
        return $this->removeItem(function () use ($work) {
            $work->qualityAssurance()->delete();
            $work->delete();
        }, ['admin.tasks.works.index', $task->uuid]);
    }

    public function content(Task $task)
    {
        $display_download_work = false;

        if (is_display_download_work_allowed($task->task_status_id)) {
            $task->loadLatestSubmittedWork();

            if ($task->submittedWorks->count() > 0) {
                $display_download_work = true;
            }

        } else {
            $task->load('status');
        }

        return inertia('Admin/Tasks/Works/Content', [
            'task'            => $task,
            'content_preview' => $task->content,
            'data'            => [
                'allow' => [
                    'download_work' => $display_download_work,
                    'submit_work'   => ($task->task_status_id == TaskStatus::IN_PROGRESS) ? true : false,
                ],
            ],
        ]);
    }

    public function editContent(Task $task)
    {
        $task->load(['content']);

        return inertia()->modal('Admin/Tasks/Works/EditContent', [           
            'data' => [
                'title'  => __('Edit Content Preview'),
            ],
            'task' => $task,
        ])->baseRoute('admin.tasks.content', $task->uuid);
    }

    public function updateContent(Request $request, Task $task)
    {
        $request->validate([
            'title'   => 'required|max:192',
            'content' => 'nullable',
        ]);      

        $task->content()->updateOrCreate(['task_id' => $task->id], [
            'title'   => $request->title,
            'content' => $request->content,
        ]);

        return redirect()->back()->withSuccess(__('Successfully updated'));

    }

    public function storeContentComment(Request $request, Task $task, TaskMessageService $taskMessageService)
    {
        $request->validate([
            'message' => 'required',
            'quote'   => 'required',
        ]);
        $taskMessageService->store($task, $request, false);

    }

}
