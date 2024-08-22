<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\ProjectManagement\Project;
use App\Models\ProjectManagement\Task;
use App\Models\ProjectManagement\TaskStatus;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return inertia('Customer/Dashboard', [
            'data' => [
                'title' => __('Dashboard'),
                'tasks' => Task::select(['id', 'uuid', 'number', 'title', 'total', 'dead_line', 'service_id', 'project_id'])
                    ->with(['project' => function ($q) {
                        $q->select(['id', 'name']);
                    }, 'service' => function ($q) {
                        $q->select(['id', 'name']);
                    }])
                    ->where('customer_id', auth()->user()->id)
                    ->where('task_status_id', TaskStatus::SUBMITTED_FOR_APPROVAL)->get(),
            ],
        ]);
    }

    public function statistics()
    {
        return response()->json([
            'wallet_balance'    => ['value' => auth()->user()->wallet()->balance()],
            'tasks_in_progress' => ['value' => Task::where('customer_id', auth()->user()->id)
                    ->where('task_status_id', TaskStatus::IN_PROGRESS)->count()],
            'projects'          => ['value' => Project::where('customer_id', auth()->user()->id)->count()],
            'tasks_for_review'  => ['value' => Task::where('customer_id', auth()->user()->id)
                    ->where('task_status_id', TaskStatus::SUBMITTED_FOR_APPROVAL)->count()],

        ]);
    }
}
