<?php

namespace App\Http\Controllers\Author;

use App\Enums\ServiceType;
use App\Enums\SpacingType;
use App\Enums\UnitType;
use App\Enums\WorkType;
use App\Events\StartedWorkingEvent;
use App\Http\Controllers\Controller;
use App\Models\ProjectManagement\Task;
use App\Models\ProjectManagement\TaskStatus;
use App\Services\ProjectManagement\TaskMessageService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        return inertia('Author/Tasks/Index', [
            'data'    => function () {
                // ALWAYS included on first visit
                // OPTIONALLY included on partial reloads
                // ONLY evaluated when needed
                return [
                    'title'     => __('Tasks'),
                    'dropdowns' => Task::authorDropdown(),
                ];
            },
            'filters' => $request->only('filters'),
            'tasks'   => Task::with(['status', 'service'])
                ->where('author_id', auth()->user()->id)
                ->archiveForAuthor($request->input('search.show_archived'))
                ->when($request->filled('search'), function ($q) use ($request) {
                    return $q->where(function ($subQuery) use ($request) {

                        if ($request->filled('search.status')) {
                            $subQuery->where('task_status_id', $request->input('search.status'));
                        }
                        if ($request->filled('search.task_number')) {
                            $subQuery->where('number', $request->input('search.task_number'));
                        }

                        return $subQuery;
                    });
                })

                ->orderBy('id', 'DESC')
                ->paginate(config('app.pagination.per_page'))
                ->withQueryString(),

        ]);
    }

    public function show(Task $task)
    {
        if ($this->isNotTaskAssignedToTheUser($task)) {
            return redirect()->route('author.tasks.index');
        }
        $eager_load = ['status'];
        
        if ($task->task_status_id == TaskStatus::COMPLETE) {
            array_push($eager_load, 'rating');
        }

        return inertia('Author/Tasks/Show', [
            'task' => $task->load($eager_load),
            'data' => [
                'briefs' => $task->details()->getFields(),
                'allow'                           => [
                    'archiving'   => ($task->task_status_id == TaskStatus::COMPLETE && !$task->is_archived_for_author) ? true : false,
                    'unarchiving' => ($task->is_archived_for_author) ? true : false,
                ],
                'statuses_allows_submitting_work' => [TaskStatus::IN_PROGRESS],
                'statuses_allows_start_working'   => [TaskStatus::NEW , TaskStatus::REQUESTED_FOR_REVISION, TaskStatus::QA_REJECTED],
                'urls'                            => [
                    'submit_work' => route('author.tasks.works.create', $task->uuid),
                ],
            ],
        ]);
    }

    public function startWorking(Task $task)
    {
        if (auth()->user()->id != $task->author_id) {
            abort(403);
        }

        $task->task_status_id = TaskStatus::IN_PROGRESS;
        $task->save();
        // Dispatching Event
        event(new StartedWorkingEvent($task));

        return redirect()->back()->withSuccess(__('Status updated'));
    }

    public function archive(Task $task)
    {
        if ($task->task_status_id != TaskStatus::COMPLETE) {
            redirect()->route('author.tasks.show', $task->uuid);
        }

        $task->is_archived_for_author = true;
        $task->save();

        return redirect()->back()->withSuccess(__('Archived'));
    }

    public function unarchive(Task $task)
    {
        $task->is_archived_for_author = null;
        $task->save();

        return redirect()->back()->withSuccess(__('Unarchived'));
    }

    public function content(Task $task)
    {
        $task->load(['status', 'content']);

        return inertia('Author/Tasks/Content', [
            'task' => $task,
            'data' => [],
        ]);
    }

    public function updateContent(Request $request, Task $task, TaskMessageService $taskMessageService)
    {
        $request->validate([
            'title'   => 'required|max:192',
            'content' => 'nullable',
        ]);

        //$content = $taskMessageService->removeContactInformation($request->content);

        $task->content()->updateOrCreate(['task_id' => $task->id], [
            'title'   => $request->title,
            'content' => $request->content,
        ]);

        return redirect()->back()->withSuccess(__('Successfully updated'));

    }

    private function isNotTaskAssignedToTheUser($task)
    {
        return ($task->author_id != auth()->user()->id);
    }

    public function calendar()
    {
        return inertia('Author/Calendar', [
            'data' => [
                'events' => Task::select(['uuid', 'number as title', 'title as description', 'dead_line_for_author as start'])
                    ->whereNotNull('dead_line_for_author')
                    ->where('author_id', auth()->user()->id)
                    ->whereNotIn('task_status_id', [TaskStatus::COMPLETE, TaskStatus::CANCELED])
                    ->get(),
            ],
        ]);
    }
}
