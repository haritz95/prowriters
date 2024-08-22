<?php

namespace App\Http\Controllers\Customer;

use Inertia\Inertia;
use App\Enums\UserType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ProjectManagement\Task;
use App\Http\Requests\StoreTaskMessageRequest;
use App\Services\ProjectManagement\TaskMessageService;

class TaskDiscussionController extends Controller
{
    private $taskMessageService;

    public function __construct(TaskMessageService $taskMessageService)
    {
        $this->taskMessageService = $taskMessageService;
    }


    public function index(Request $request, Task $task)
    {
        $task->load('status');

        $data['user_types'] = UserType::get();
        $data['urls'] = [
            'new_message' => route('customer.tasks.discussions.create', $task->uuid),
        ];

        return inertia('Customer/Tasks/Discussions/Index', [
            'tab' => 'discussions',
            'task' => $task,
            'messages' =>  $this->taskMessageService->getExternalDiscussions($task, $request),
            'data' => $data,
        ]);
    }

    public function create(Task $task)
    {
        $task->load('status');

        return Inertia::modal('Customer/Tasks/Discussions/Create', [           
            'task' => $task,
            'data' => [
                'config' => $this->taskMessageService->getConfigForCreateMessage(route('customer.tasks.discussions.store', $task->uuid)),
                'urls' => [
                    'message_list' => route('customer.tasks.discussions.index', $task->uuid),
                ]
            ]
        ])->baseRoute('customer.tasks.discussions.index', $task->uuid);
    }

    function store(StoreTaskMessageRequest $request, Task $task)
    {
        $this->taskMessageService->store($task, $request, TRUE);

        return redirect()->route('customer.tasks.discussions.index', $task->uuid)->withSuccess(__('Message sent successfully'));
    }
}
