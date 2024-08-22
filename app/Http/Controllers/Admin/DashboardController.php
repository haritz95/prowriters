<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PermissionType;
use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Models\Billing\Bill;
use App\Models\ProjectManagement\Task;
use App\Models\ProjectManagement\TaskStatus;
use App\Models\User;
use App\Traits\PushNotificationTrait;
use Carbon\Carbon;
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
        if (auth()->user()->hasRole(PermissionType::ROLE_SUPER_ADMIN)) {
            return $this->superAdminDashboard();
        } else {
            return inertia('Admin/DashboardForEditor', [
                'data' => [
                    'title' => __('Dashboard'),
                    'tasks' => Task::select(['id', 'uuid', 'number', 'title', 'dead_line', 'dead_line_for_author', 'service_id'])
                        ->with(['service' => function ($q) {
                            $q->select(['id', 'name']);
                        }])
                        ->where('editor_id', auth()->user()->id)
                        ->where('task_status_id', TaskStatus::SUBMITTED_FOR_QA)->get(),
                ],
            ]);
        }

    }

    private function superAdminDashboard()
    {
        return inertia('Admin/Dashboard', [
            'data' => [
                'title' => __('Dashboard'),              
            ],
        ]);
    }

    private function customersCount()
    {
        return User::whereBetween(DB::raw('DATE(created_at)'), [Carbon::now()->subDays(7)->toDateString(), Carbon::today()->toDateString()])
            ->where('type', UserType::CUSTOMER)->count();
    }

    private function tasksCount()
    {
        return Task::where('task_status_id', TaskStatus::IN_PROGRESS)->count();
    }

    private function unAssignedTasksCount()
    {
        return Task::whereNull('author_id')->whereNot('task_status_id', TaskStatus::CANCELED)->count();
    }

    // private function paidBillsAmount()
    // {
    //     return Bill::whereBetween('paid', [Carbon::now()->subDays(30)->toDateString(), Carbon::today()->toDateString()])->get()->sum('total');
    // }
    
    private function unPaidBillsAmount()
    {
        return Bill::whereNull('paid')->get()->sum('total');
    }

    private function profitAmount()
    {
        return $this->getProfit(Carbon::now()->subDays(30)->toDateString(), Carbon::today()->toDateString());
    }

    private function orderCountByStatus($status)
    {
        return Task::where('task_status_id', $status)->count();
    }

    private function unInvoicedTasksCount()
    {
        return Task::whereNotNull('task_status_id')
            ->where('task_status_id', '<>', TaskStatus::CANCELED)->whereNull('invoice_id')->count();
    }

    public function statistics(Request $request)
    {
        switch ($request->name) {
            case 'customers':
                $data = $this->customersCount();
                break;
            case 'tasks':
                $data = $this->tasksCount();
                break;
            case 'paid_bills_amount':
                $data = $this->paidBillsAmount();
                break;
            case 'unpaid_bills_amount':
                $data = $this->unPaidBillsAmount();
                break;
            case 'profit_amount':
                $data = $this->profitAmount();
                break;
            case 'requested_for_revision':
                $data = $this->orderCountByStatus(TaskStatus::REQUESTED_FOR_REVISION);
                break;
            case 'submitted_for_approval':
                $data = $this->orderCountByStatus(TaskStatus::SUBMITTED_FOR_APPROVAL);
                break;
            case 'submitted_for_qa':
                $data = $this->orderCountByStatus(TaskStatus::SUBMITTED_FOR_QA);
                break;
            case 'unassigned_tasks':
                $data = $this->unAssignedTasksCount();
            case 'uninvoiced_tasks':
                $data = $this->unInvoicedTasksCount();
                break;
            default:
                $data = 0;
                break;
        }

        return response()->json($data);
    }

    public function incomeGraph()
    {
        $data = [];
        for ($i = 4; $i >= 0; $i = $i - 1) {
            $date = now()->subMonths($i);

            $start = $date->copy()->startofMonth()->toDateString();
            $end   = $date->copy()->endofMonth()->toDateString();

            $profit                            = $this->getProfit($start, $end);
            $data['values'][]                  = $profit;
            $data['labels'][]                  = translate_month_from_english($date->format('F'));
            $data['formatted_values'][$profit] = format_money($profit);
        }

        return response()->json($data);
    }

    private function getProfit($start, $end)
    {
        return Task::where('task_status_id', TaskStatus::COMPLETE)
            ->whereNotNull('invoice_id')
        // ->whereBetween('created_at', [$start, $end])
            ->whereBetween(DB::raw('DATE(created_at)'), [$start, $end])
            ->sum(DB::raw('total - IFNULL(author_payment_amount, 0)'));
    }
}
