<?php

namespace App\Http\Controllers\Author;

use App\Events\TaskSelfAssignedEvent;
use App\Http\Controllers\Controller;
use App\Models\Author\AuthorProfile;
use App\Models\ProjectManagement\Task;
use App\Models\ProjectManagement\TaskStatus;
use Illuminate\Http\Request;

class FindWorkController extends Controller
{
    public function index(Request $request)
    {
        $profile = $this->getAuthorProfile();

        $author_level_value = $profile->authorLevel->numeric_value;

        $services = array_filter([$profile->service_id_1, $profile->service_id_2, $profile->service_id_3]);

        return inertia('Author/Tasks/FindWork/Index', [
            'data'    => function () {
                // ALWAYS included on first visit
                // OPTIONALLY included on partial reloads
                // ONLY evaluated when needed
                return [
                    'title' => __('Find Works'),
                    // 'dropdowns' => Task::authorDropdown(),
                ];
            },
            'filters' => $request->only('filters'),
            'tasks'   => Task::with(['status', 'service'])
                ->whereNull('author_id')
                ->whereNotNull('task_status_id')
                ->withWhereHas('authorLevel', function ($q) use ($author_level_value) {
                    $q->where('numeric_value', '<=', $author_level_value);
                })
                ->whereIn('service_id', $services)
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
        if ($task->author_id) {
            return redirect()->route('author.find.work.index')->withFail(__('The task is already assigned'));
        }

        $task->load(['status']);

        if ((!is_self_assigning_tasks_enable()) || $task->task_status_id != TaskStatus::NEW ) {
            return redirect()->route('author.tasks.index');
        }

        return inertia('Author/Tasks/FindWork/Show', [
            'task' => $task,
            'data' => [
                'title'         => __('Find work') . ' > ' . __('Task') . ' ' . $task->number,
                'briefs' => $task->details()->getFields(),
            ],
        ]);
    }

    private function getAuthorProfile()
    {
        return AuthorProfile::select(['id', 'author_level_id', 'service_id_1', 'service_id_2', 'service_id_3'])
            ->with(['authorLevel'])->where('user_id', auth()->user()->id)
            ->get()
            ->first();
    }

    private function isNotAllowedToAcceptTask()
    {
        $profile                           = $this->getAuthorProfile();
        $allowed_number_of_tasks_at_a_time = $profile->authorLevel->number_of_tasks_at_a_time;

        $tasks_in_progress = Task::where('author_id', auth()->user()->id)->where('task_status_id', TaskStatus::IN_PROGRESS)->count();

        // -1 means unlimited
        if ($allowed_number_of_tasks_at_a_time == '-1') {
            return false;
        }

        if ($tasks_in_progress < $allowed_number_of_tasks_at_a_time) {
            return false;
        }
        return true;
    }

    public function accept(Task $task)
    {
        if ($task->author_id) {
            return redirect()->route('author.find.work.index')->withFail(__('The task is already assigned'));
        }

        if ($this->isNotAllowedToAcceptTask()) {
            return redirect()->route('author.find.work.index')->withFail(__('You are not allowed to accept any task when you already have tasks in progress'));
        }

        $task->author_id = auth()->user()->id;
        $task->save();

        event(new TaskSelfAssignedEvent($task));

        return redirect()->route('author.tasks.show', $task->uuid)->withSuccess(__('The task has been assigned to you'));

    }

}
