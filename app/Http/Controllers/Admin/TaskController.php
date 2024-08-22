<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ServiceType;
use App\Events\TaskAssignedEvent;
use App\Events\TaskEditorAssignedEvent;
use App\Events\TaskStatusChangedEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Attachment;
use App\Models\Business\Service;
use App\Models\ProjectManagement\Task;
use App\Models\ProjectManagement\TaskStatus;
use App\Models\User;
use App\Services\PriceCalculatorService;
use App\Services\ProjectManagement\TaskCreateService;
use App\Services\ProjectManagement\TaskDropdownService;
use App\Services\ProjectManagement\TaskUpdateService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class TaskController extends Controller
{
    public function servicesList(Request $request)
    {
        return inertia('Admin/Tasks/Services', [
            'data' => [
                'title'      => __('Select a service'),
                'services'   => Service::active()->whereNull('not_available_for_direct_order')->get(),
                'route_name' => 'admin.tasks.create',
            ],
        ]);
    }

    public function index(Request $request)
    {
        return inertia('Admin/Tasks/Index', [
            'data'    => function () {
                // ALWAYS included on first visit
                // OPTIONALLY included on partial reloads
                // ONLY evaluated when needed
                return [
                    'title'      => __('Tasks'),
                    'dropdowns'  => Task::adminDropdown(),
                    'statistics' => Task::statistics(),
                ];
            },
            'filters' => $request->only('search'),
            'tasks'   => Task::with([
                'status', 'service', 'authorLevel',
                'author'   => function ($query) {
                    $query->basicInfo();
                },
                'customer' => function ($query) {
                    $query->basicInfo();
                },
            ])
                ->whereNotNull('task_status_id')
                ->archiveForAdmin($request->input('search.show_archived'))
                ->taskOrderBy($request->input('search.order_by'))
                ->when($request->filled('search.task_date') && is_array($request->input('search.task_date')), function ($query) use ($request) {
                    $date = format_date_range_from_client_side($request->input('search.task_date'));
                    return $query->whereDateBetween('created_at', $date['from'], $date['to']);

                })
                ->when($request->filled('search.service'), function ($query) use ($request) {
                    return $query->where('service_id', $request->input('search.service'));
                })
                ->when($request->filled('search.customer'), function ($query) use ($request) {
                    return $query->where('customer_id', $request->input('search.customer'));
                })
                ->when($request->filled('search.author'), function ($query) use ($request) {
                    return $query->where('author_id', $request->input('search.author'));
                })
                ->when($request->filled('search.service_level_id'), function ($query) use ($request) {
                    return $query->where('service_level_id', $request->input('search.service_level_id'));
                })
                ->when($request->filled('search.author_level_id'), function ($query) use ($request) {
                    return $query->where('author_level_id', $request->input('search.author_level_id'));
                })
                ->when($request->filled('search.status'), function ($query) use ($request) {
                    return $query->where('task_status_id', $request->input('search.status'));
                })
                ->when($request->filled('search.not_invoiced'), function ($query) use ($request) {

                    if (is_boolean_true($request->input('search.not_invoiced'))) {
                        return $query->whereNull('invoice_id');
                    }

                })
                ->when($request->filled('search.not_assigned'), function ($query) use ($request) {

                    if (is_boolean_true($request->input('search.not_assigned'))) {
                        return $query->whereNull('author_id');
                    }

                })
                ->when($request->filled('search.task_number'), function ($query) use ($request) {
                    return $query->where('number', $request->input('search.task_number'));
                })
                ->when($request->filled('search.task_title'), function ($query) use ($request) {
                    return $query->where('title', 'like', '%' . $request->input('search.task_title') . '%');
                })
                ->orderBy('id', 'DESC')
                ->paginate(config('app.pagination.per_page'))
                ->withQueryString(),

        ]);
    }

    public function create(Service $service, TaskDropdownService $taskDropdownService)
    {
        $attachment_configuration = Attachment::configForCreateTask($service);

        $dropdowns              = $taskDropdownService->get($service);
        $dropdowns['customers'] = [];

        $fields                        = $dropdowns['fields'];
        $fields['customer_id']         = null;
        $fields['is_total_overridden'] = null;
        $fields['total']               = 0;
        $fields['original_total']      = 0;
        $fields['updated_total']       = 0;

        unset($dropdowns['fields']);

        return inertia('Admin/Tasks/Create', [
            'data' => [
                'dropdowns'       => $dropdowns,
                'title'           => __('New Task'),
                'service'         => $service,
                'service_types'   => ServiceType::getForFrontEnd(),
                'existing_record' => $fields,
                'urls'            => [
                    'add_to_cart' => route('admin.tasks.store', $service->slug),
                ],
                'config'          => $attachment_configuration,
                'is_admin'        => true,
            ],

        ]);
    }

    public function store(StoreOrderRequest $request, Service $service, PriceCalculatorService $priceCalculatorService)
    {
        $data              = $priceCalculatorService->calculate($service, $request);
        $fields            = $data['fields'];
        $fields['user_id'] = auth()->user()->id;
        $task              = (new TaskCreateService())($fields);

        return redirect()->route('admin.tasks.show', $task->uuid);
    }

    public function show(Task $task)
    {
        return inertia('Admin/Tasks/Show', [
            'task' => $task->load(['status', 'details', 'additionalServices', 'bidRequest', 'customer' => function ($query) {
                $query->basicInfo();
            }, 'author' => function ($query) {
                $query->basicInfo();
            }, 'editor' => function ($query) {
                $query->basicInfo();
            }]),
            'data' => [
                'is_a_follower' => $task->isAFollower(auth()->user()->id),
                'allow'         => [
                    'archiving'   => ($task->task_status_id == TaskStatus::COMPLETE && !$task->is_archived_for_admin) ? true : false,
                    'unarchiving' => ($task->is_archived_for_admin) ? true : false,
                ],
                'briefs'        => $task->details()->getFields(),
            ],
        ]);
    }

    public function edit(Task $task, TaskDropdownService $taskDropdownService)
    {
        if ($task->invoice_id) {
            return redirect()->route('admin.tasks.show', $task->uuid)->withFail(__('Cannot edit the task'));
        }
        $task->load(['service', 'details', 'additionalServices', 'customer' => function ($q) {
            return $q->basicInfo();
        }]);

        $service = $task->service;

        $task = $task->toArray();

        $attachment_configuration = Attachment::configForCreateTask($service);

        $dropdowns              = $taskDropdownService->get($service);
        $dropdowns['customers'] = [User::find($task['customer_id'])];

        $fields = $dropdowns['fields'];
        unset($dropdowns['fields']);

        if ($task['is_total_overridden']) {
            $task['updated_total'] = $task['total'];
            $task['total']         = $task['original_total'];
        }

        $task['added_additional_services'] = $task['additional_services'];
        unset($task['additional_services']);

        return inertia('Admin/Tasks/Create', [
            'data' => [
                'dropdowns'       => $dropdowns,
                'title'           => __('Edit Task'),
                'service'         => $service,
                'service_types'   => ServiceType::getForFrontEnd(),
                'existing_record' => array_merge($fields, $task, $task['details']),
                'urls'            => [
                    'add_to_cart' => route('admin.tasks.update', ['task' => $task['uuid']]),
                ],
                'config'          => $attachment_configuration,
                'is_admin'        => true,
                'is_edit_on_mode' => true,
            ],

        ]);
    }

    public function update(StoreOrderRequest $request, Task $task, PriceCalculatorService $priceCalculatorService, TaskUpdateService $taskUpdateService)
    {
        $data = $priceCalculatorService->calculate($task->service, $request);

        $task = $taskUpdateService->update($task, $data['fields']);

        return redirect()->route('admin.tasks.show', $task->uuid);
    }

    public function editStatus(Task $task)
    {
        $task->load('status');

        return Inertia::modal('Admin/Tasks/ChangeStatus', [
            'task' => $task,
            'data' => [
                'title'         => __('Change status'),
                'task_statuses' => TaskStatus::all(),
            ],
        ])->baseRoute('admin.tasks.show', $task->uuid);
    }

    public function updateStatus(Request $request, Task $task)
    {
        $request->validate([
            'task_status_id' => 'required|exists:task_statuses,id',
        ], [
            'task_status_id.required' => __('Please select a status'),
        ]);

        $previous             = $task->status->name;
        $task->task_status_id = $request->task_status_id;

        if ($request->task_status_id == TaskStatus::COMPLETE) {
            $task->accepted_at = now();
        }

        $task->save();
        $task->refresh();
        $new = $task->status->name;

        // Dispatching Event
        event(new TaskStatusChangedEvent($task, $previous, $new));

        return redirect()->route('admin.tasks.show', $task->uuid)->withSuccess(__('Status updated'));
    }

    public function editAssignee(Task $task)
    {
        $task->load('status');

        return Inertia::modal('Admin/Tasks/Assignee', [
            'task' => $task,
            'data' => [
                'assignees' => User::select('id', 'first_name', 'last_name', 'code', 'photo')->authors()->get(),
                'title'     => __('Assign Task'),
            ],
        ])->baseRoute('admin.tasks.show', $task->uuid);
    }

    public function updateAssignee(Request $request, Task $task)
    {
        $request->validate([
            'author_id'             => 'nullable',
            'dead_line_for_author'  => 'required|date:format:' . config('app.date.form_format'),
            'author_payment_amount' => 'required|min:0|regex:/^\d+(\.\d{1,2})?$/',

        ], [
            // 'author_id.required'             => __('Please select a Author'),
            'author_payment_amount.required' => __('Payment amount is required'),
            'author_payment_amount.regex'    => __('Not a valid amount'),
            'author_payment_amount.min'      => __('Payment amount must be at least 0'),
        ]);

        $task->author_payment_amount = $request->author_payment_amount;
        $task->dead_line_for_author  = Carbon::parse($request->dead_line_for_author)->setTimezone('UTC')->format('Y-m-d H:i:s');

        $old_author_id = $task->author_id;

        if ($request->author_id) {
            $task->author_id = $request->author_id;
        } else {
            $task->author_id = null;
        }

        $task->save();

        add_task_follower($task, $task->author_id, $old_author_id);

        //Dispatching Event
        event(new TaskAssignedEvent($task));

        return redirect()->route('admin.tasks.show', $task->uuid)->withSuccess(__('Successfully updated'));
    }

    public function editEditor(Task $task)
    {
        $task->load('status');

        return Inertia::modal('Admin/Tasks/Editor', [
            'task' => $task,
            'data' => [
                'assignees' => User::select('id', 'first_name', 'last_name', 'code', 'photo')
                    ->editors()->get(),
                'title'     => __('Assign Editor for Quality Control'),
            ],
        ])->baseRoute('admin.tasks.show', $task->uuid);
    }

    public function updateEditor(Request $request, Task $task)
    {
        $old_editor_id = $task->editor_id;

        if ($request->editor_id) {
            $task->editor_id = $request->editor_id;
        } else {
            $task->editor_id = null;
        }

        $task->save();

        add_task_follower($task, $task->editor_id, $old_editor_id);

        //Dispatching Event
        event(new TaskEditorAssignedEvent($task));

        return redirect()->route('admin.tasks.show', $task->uuid)->withSuccess(__('Successfully updated'));
    }

    public function changeDates(Task $task)
    {
        return Inertia::modal('Admin/Tasks/ChangeDates', [
            'task' => $task,
            'data' => [
                'title'                => __('Change Dates'),
                'urls'                 => [
                    'submit_form' => route('admin.tasks.update.dates', $task->uuid),
                ],
                'dead_line'            => $task->dead_line,
                'dead_line_for_author' => $task->dead_line_for_author,
            ],
        ])->baseRoute('admin.tasks.show', $task->uuid);
    }

    public function updateDates(Request $request, Task $task)
    {
        $request->validate([
            'dead_line'            => 'nullable|date:format:' . config('app.date.form_format'),
            'dead_line_for_author' => 'nullable|date:format:' . config('app.date.form_format'),
        ]);

        if ($request->dead_line) {
            $task->dead_line = format_date_from_client_side($request->dead_line);
        } else {
            $task->dead_line = null;
        }
        if ($request->dead_line_for_author) {

            $task->dead_line_for_author = format_date_from_client_side($request->dead_line_for_author);
        } else {
            $task->dead_line_for_author = null;
        }
        $task->save();

        return redirect()->route('admin.tasks.show', $task->uuid)->withSuccess(__('Successfully updated'));
    }

    public function follow(Task $task)
    {
        $task->followers()->attach(auth()->user()->id);

        return redirect()->back()->withSuccess(__('Following'));
    }

    public function unFollow(Task $task)
    {
        $task->followers()->detach(auth()->user()->id);

        return redirect()->back()->withSuccess(__('Unfollowed'));
    }

    public function archive(Task $task)
    {
        if ($task->task_status_id != TaskStatus::COMPLETE) {
            redirect()->route('admin.tasks.show', $task->uuid);
        }

        $task->is_archived_for_admin = true;
        $task->save();

        return redirect()->back()->withSuccess(__('Archived'));
    }

    public function unarchive(Task $task)
    {
        $task->is_archived_for_admin = null;
        $task->save();

        return redirect()->back()->withSuccess(__('Unarchived'));
    }

    public function destroy(Task $task)
    {
        DB::beginTransaction();

        try {
            $task->delete();

            $route = redirect()->route('admin.tasks.index')->withSuccess(__('Successfully deleted'));

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            $route = redirect()->route('admin.tasks.show', $task->uuid)->withFail(__('You cannot delete the task'));
            DB::rollback();
        } catch (\Exception $e) {

            $route = redirect()->route('admin.tasks.show', $task->uuid)->withFail(__('Could not perform the requested action'));
            DB::rollback();
        }

        return $route;
    }

    public function financial(Task $task)
    {
        $task->load(['status', 'bidRequest', 'invoice' => function ($q) {
            $q->select('id', 'uuid', 'number');
        }]);

        return inertia('Admin/Tasks/Financial/Index', [
            'task' => $task,
            'data' => [
                'financial' => (!$task->bidRequest) ? $task->details()->getFinancialFields() : null,
            ],
        ]);
    }

    public function calendar()
    {
        return inertia('Admin/Calendar', [
            'data' => [
                'title'  => __('Calendar'),
                'events' => Task::select(['uuid', 'number as title', 'title as description', 'dead_line as start'])
                    ->whereNotNull('dead_line')
                    ->whereNotIn('task_status_id', [TaskStatus::COMPLETE, TaskStatus::CANCELED])
                    ->get(),
            ],
        ]);
    }
}
