<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Enums\UserType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ProjectManagement\Task;
use App\Http\Requests\StoreTaskMessageRequest;
use App\Services\ProjectManagement\TaskMessageService;

class TaskInternalDiscussionController extends Controller
{
    private $taskMessageService;

    public function __construct(TaskMessageService $taskMessageService)
    {
        $this->taskMessageService = $taskMessageService;
    }


    public function index(Request $request, Task $task)
    {
        $task->load('status');

        return inertia('Admin/Tasks/Discussions/Index', [
            'tab' => 'internal-discussions',
            'task' => $task,
            'messages' =>  $this->taskMessageService->getInternalDiscussions($task, $request),
            'data'     => [
                'user_types' => UserType::get(),
                'discussion_parties' => __('Discussions among Author, Admin and Editor'),
                'urls' => [
                    'new_message' => route('admin.tasks.internal-discussions.create', $task->uuid),
                ]

            ],
        ]);
    }

    public function create(Task $task)
    {
        $task->load('status');

        return Inertia::modal('Admin/Tasks/Discussions/Create', [           
            'task' => $task,
            'data' => [
                'config' => $this->taskMessageService->getConfigForCreateMessage(route('admin.tasks.internal-discussions.store', $task->uuid)),
                'urls' => [
                    'message_list' => route('admin.tasks.internal-discussions.index', $task->uuid),
                ]
            ]
        ])->baseRoute('admin.tasks.internal-discussions.index', $task->uuid);
    }

    function store(StoreTaskMessageRequest $request, Task $task)
    {
        $this->taskMessageService->store($task, $request, FALSE);

        return redirect()->route('admin.tasks.internal-discussions.index', $task->uuid)->withSuccess(__('Message sent successfully'));
    }
}
