<?php

namespace App\Http\Controllers\Author;

use Inertia\Inertia;
use App\Models\Attachment;
use App\Http\Controllers\Controller;
use App\Models\ProjectManagement\Task;
use App\Http\Requests\StoreTaskAttachmentRequest;
use App\Services\ProjectManagement\TaskAttachmentService;

class TaskAttachmentController extends Controller
{
    private $taskAttachmentService;

    public function __construct(TaskAttachmentService $taskAttachmentService)
    {
        $this->taskAttachmentService = $taskAttachmentService;
    }

    public function index(Task $task)
    {
        $task->load('status');
        return inertia('Author/Tasks/Attachments/Index', [
            'task' => $task,
            'attachments' => $task->attachments()->paginate(config('app.pagination.per_page')),
            'data' => [
                'urls' => [
                    'create_attachment' => route('author.tasks.attachments.create', $task->uuid),
                ]
            ]
        ]);
    }

    public function create(Task $task)
    {
        $task->load('status');

        return Inertia::modal('Author/Tasks/Attachments/Create', [
            'task' => $task,
            'data' => [
                'config' => $this->taskAttachmentService->getConfigForCreateAttachment(route('author.tasks.attachments.store', $task->uuid))
            ]
        ])->baseRoute('author.tasks.attachments.index', $task->uuid);
    }

    function store(StoreTaskAttachmentRequest $request, Task $task)
    {
        $this->taskAttachmentService->store($task, $request);
        return redirect()->route('author.tasks.attachments.index', $task->uuid)->withSuccess(__('Successfully uploaded'));
    }

    public function destroy(Task $task, Attachment $attachment)
    {
        $attachment->delete();
        return redirect()->route('author.tasks.attachments.index', $task->uuid)->withSuccess(__('Successfully deleted'));
    }
}
