<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Billing\Bill;
use App\Models\ProjectManagement\Task;
use App\Models\ProjectManagement\TaskStatus;
use App\Traits\PushNotificationTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    use PushNotificationTrait;

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return inertia('Author/Dashboard', [
            'data' => [
                'title'             => __('Dashboard'),
                'announcements'     => Announcement::whereNull('inactive')->orderBy('id', 'DESC')->get(),
                'tasks_in_progress' => Task::select(['id', 'uuid', 'number', 'title', 'author_payment_amount', 'dead_line_for_author', 'service_id'])
                    ->with(['service' => function ($q) {
                        $q->select(['id', 'name']);
                    }])
                    ->where('author_id', auth()->user()->id)
                    ->where('task_status_id', TaskStatus::IN_PROGRESS)->get(),

                'tasks_requires_revision' => Task::select(['id', 'uuid', 'number', 'title', 'author_payment_amount', 'dead_line_for_author', 'service_id'])
                    ->with(['service' => function ($q) {
                        $q->select(['id', 'name']);
                    }])
                    ->where(function ($q) use ($request) {
                        return $q->where('author_id', auth()->user()->id)
                            ->whereIn('task_status_id', [TaskStatus::REQUESTED_FOR_REVISION, TaskStatus::QA_REJECTED]);
                    })
                    ->get(),
            ],
        ]);
    }

    public function statistics(Bill $bill)
    {
        $statistics = DB::table('tasks')
            ->selectRaw('count(IF(task_status_id = ?, 1, null))  AS task_in_progress', [TaskStatus::IN_PROGRESS])
            ->selectRaw('count(IF(task_status_id = ?, 1, null))  AS task_in_revision', [TaskStatus::REQUESTED_FOR_REVISION])
            ->where('author_id', auth()->user()->id)->get()->first();

        return response()->json([
            'tasks_in_progress'      => ['value' => $statistics->task_in_progress],
            'tasks_require_revision' => ['value' => $statistics->task_in_revision],
            'number_of_messages'     => ['value' => 0],
            'billable_amount'        => ['value' => format_money($bill->getTotalBillableAmount($bill->getBillableTasks(auth()->user()->id)))],
            'outstanding_payments'   => ['value' => format_money(Bill::outstandingPaymentAmount(auth()->user()->id))],

        ]);
    }

}
