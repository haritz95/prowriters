<?php

namespace App\Http\Controllers\Admin;

use App\Enums\InvoiceStatusType;
use App\Http\Controllers\Controller;
use App\Models\Accounting\Invoice;
use App\Models\Billing\Bill;
use App\Models\ProjectManagement\Task;
use App\Models\ProjectManagement\TaskStatus;
use App\Models\Wallets\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;

class ReportController extends Controller
{

    public function incomeStatement(Request $request)
    {
        $data['title'] = __('Income Statement');

        if ($request->date_range) {
            try {

                $parsed_date        = format_date_range_from_client_side($request->date_range);
                $data['date_range'] = $request->date_range;
                $data['from']       = $parsed_date['from'];
                $data['to']         = $parsed_date['to'];

                // Income Statement 1

                $data['income_statement_1_sales_revenue'] = Invoice::whereBetween(DB::raw('DATE(invoice_date)'), [
                    $parsed_date['from'],
                    $parsed_date['to'],
                ])
                    ->where('invoice_status_id', '<>', InvoiceStatusType::FORWARDED)->sum('total');
                
                $data['income_statement_1_sales_tax_amount'] = Invoice::whereBetween(DB::raw('DATE(invoice_date)'), [
                    $parsed_date['from'],
                    $parsed_date['to'],
                ])
                    ->where('invoice_status_id', '<>', InvoiceStatusType::FORWARDED)->sum('sales_tax_amount');

                $data['income_statement_1_total_payment_to_authors'] = Bill::whereBetween(DB::raw('DATE(paid)'), [
                    $parsed_date['from'],
                    $parsed_date['to'],
                ])->sum('total');

                $data['income_statement_1_net_income'] = $data['income_statement_1_sales_revenue'] - ($data['income_statement_1_total_payment_to_authors'] + $data['income_statement_1_sales_tax_amount']);

                // Income Statement 2

                $select = DB::raw("service_id, SUM(IFNULL(total,0)) as total, SUM(IFNULL(author_payment_amount, 0)) AS payroll_expense");

                $record = Task::select($select)->with(['service' => function ($q) {
                    $q->select(['id', 'name']);
                }])
                    ->where('task_status_id', TaskStatus::COMPLETE)
                    ->whereBetween(DB::raw('DATE(accepted_at)'), [
                        $parsed_date['from'],
                        $parsed_date['to'],
                    ])
                    ->groupBy('service_id')
                    ->get();

                if ($record->count() > 0) {
                    $data['income_statement_2_records']                      = $record;
                    $data['income_statement_2_sales_revenue']                = $record->sum('total');
                    $data['income_statement_2_total_payment_to_authors'] = $record->sum('payroll_expense');
                    $data['income_statement_2_net_income']                   = $data['income_statement_2_sales_revenue'] - $data['income_statement_2_total_payment_to_authors'];
                } else {
                    $data['income_statement_2_records']                      = null;
                    $data['income_statement_2_sales_revenue']                = 0;
                    $data['income_statement_2_total_payment_to_authors'] = 0;
                    $data['income_statement_2_net_income']                   = 0;
                }

                $data['report_generated'] = true;

            } catch (\Exception$e) {
            }
        } else {
            $parsed_date['from']      = null;
            $parsed_date['to']        = null;
            $data['date_range']       = null;
            $data['report_generated'] = false;
        }

        return inertia('Admin/Reports/IncomeStatement', [
            'data' => $data,
        ]);

    }

    public function activityLog(Request $request)
    {
        return inertia('Admin/Reports/ActivityLog', [
            'data' => function () {
                // ALWAYS included on first visit
                // OPTIONALLY included on partial reloads
                // ONLY evaluated when needed
                return [
                    'title'       => __('Activity Log'),                    

                ];
            },
            'filters'     => $request->only('filters'),
            'activities' => Activity::orderBy('created_at', 'DESC')
                ->paginate(config('app.pagination.per_page'))
                ->withQueryString(),
        ]);
    }
   

    public function datatable_activity_log(Request $request)
    {
        $activity = Activity::orderBy('created_at', 'DESC');

        return Datatables::eloquent($activity)->addColumn('causer_name', function ($activity) {

            return '<a href="' . route('user_profile', $activity->causer->id) . '">' . $activity->causer->full_name . '</a>';
        })
            ->addColumn('date', function ($activity) {
                return convertToLocalTime($activity->created_at);
            })

            ->rawColumns([
                'date',
                'causer_name',
                'description',
            ])

            ->make(true);
    }

    public function destroy_activity()
    {
        Activity::truncate();

        return redirect()->back()->withSuccess(__('Activities deleted'));
    }

    public function walletBalance()
    {
        return inertia('Admin/Reports/WalletBalance', [
            'data' => [
                'balance' => Wallet::sum('balance'),
            ],
        ]);
    }
}
