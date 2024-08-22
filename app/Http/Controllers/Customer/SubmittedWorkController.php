<?php

namespace App\Http\Controllers\Customer;

use App\Events\RequestedForRevisionEvent;
use App\Events\WorkAcceptedEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskMessageRequest;
use App\Models\Business\Urgency;
use App\Models\ProjectManagement\RevisionRequest;
use App\Models\ProjectManagement\Task;
use App\Models\ProjectManagement\TaskStatus;
use App\Services\AttachmentService;
use App\Services\ProjectManagement\TaskMessageService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SubmittedWorkController extends Controller
{    

    public function storeContentComment(Request $request, Task $task, TaskMessageService $taskMessageService)
    {
        $request->validate([
            'message' => 'required',
            'quote'   => 'required',
        ]);
        $taskMessageService->store($task, $request, true);

    }

    public function accept(Task $task)
    {
        if ($task->task_status_id == TaskStatus::SUBMITTED_FOR_APPROVAL) {

            $task->task_status_id = TaskStatus::COMPLETE;
            $task->accepted_at    = now();
            $task->save();

            // Dispatching Event
            event(new WorkAcceptedEvent($task));

            return redirect()->route('customer.tasks.ratings.create', $task->uuid)->withSuccess(__('Thank you very much. Your task is now marked as complete'));
        } else {
            return redirect()->route('customer.dashboard');
        }
    }

    public function createRequestForRevision(Task $task, TaskMessageService $taskMessageService)
    {
        if ($task->task_status_id != TaskStatus::SUBMITTED_FOR_APPROVAL) {
            return redirect()->route('customer.tasks.show', $task->uuid);
        }

        if ($this->revisionIsNotAllowed($task)) {
            return redirect()->route('customer.tasks.show', $task->uuid)->withFail(__('You can not request for revision for this task'));
        }

        return Inertia::modal('Customer/Tasks/Content/RequestForRevision', [
            'task' => $task,
            'data' => [
                'title'  => __('Request for revision'),
                'config' => $taskMessageService->getConfigForCreateMessage(route('customer.tasks.revisions.store', $task->uuid)),
            ],
        ])->baseRoute('customer.tasks.show', $task->uuid);
    }

    public function storeRequestForRevision(StoreTaskMessageRequest $request, Task $task)
    {
        if ($task->task_status_id != TaskStatus::SUBMITTED_FOR_APPROVAL) {
            return redirect()->route('customer.dashboard');
        }
        if ($this->revisionIsNotAllowed($task)) {
            return redirect()->route('customer.tasks.content', $task->uuid)->withFail(__('You can not request for revision for this task'));
        }

        // Get the submitted work
        $submittedWork = $task->submittedWorks()->latest('created_at')->first();

        // Extend the deadline by default value set by the admin
        $dead_line_extension_by_value = settings('dead_line_extension_by_value');
        $dead_line_extension_by_type  = settings('dead_line_extension_by_type');

        if ($dead_line_extension_by_type == Urgency::TYPE_DAYS) {
            $new_dead_line                = $task->dead_line->addDays($dead_line_extension_by_value);
            $new_dead_line_for_author = $task->dead_line_for_author->addDays($dead_line_extension_by_value);
        } else if ($dead_line_extension_by_type == Urgency::TYPE_HOURS) {
            $new_dead_line                = $task->dead_line->addHours($dead_line_extension_by_value);
            $new_dead_line_for_author = $task->dead_line_for_author->addHours($dead_line_extension_by_value);
        } else {
            $new_dead_line                = $task->dead_line;
            $new_dead_line_for_author = $task->dead_line_for_author;
        }

        // Update Task Status
        $task->task_status_id           = TaskStatus::REQUESTED_FOR_REVISION;
        $task->revisions_taken          = ($task->revisions_taken) ? $task->revisions_taken + 1 : 1;
        $task->dead_line                = $new_dead_line;
        $task->dead_line_for_author = $new_dead_line_for_author;
        $task->save();

        // Create Revision Request
        $revisionRequest = RevisionRequest::create([
            'submitted_work_id' => $submittedWork->id,
            'user_id'           => auth()->user()->id,
            'message'           => $request->message,
        ]);

        // Save the attachments
        (new AttachmentService($revisionRequest, $request->input('files'), auth()->user()->id))->save();

        // Dispatching Event
        event(new RequestedForRevisionEvent($task));

        return redirect()->route('customer.tasks.show', $task->uuid)->withSuccess(__('Revision request has been sent successfully'));
    }

    private function revisionIsNotAllowed($task)
    {
        $number_of_revision_allowed = ($task->revisions_allowed) ? $task->revisions_allowed : -1;

        if ($number_of_revision_allowed == -1) {
            return false;
        }

        if ($task->revisions_taken < $number_of_revision_allowed) {
            return false;
        }

        return false;
    }
}
