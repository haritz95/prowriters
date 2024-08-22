<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wallets\WalletAdjustment;
use Illuminate\Http\Request;

class WalletAdjustmentController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return inertia('Admin/Transactions/WalletAdjustments/Index', [
            'data'         => [
                'title' => __('Wallet Adjustments'),
                'urls'  => [
                    'search' => route('admin.walletAdjustments.index'),
                ],
            ],
            'filters'      => $request->only('filters'),
            'transactions' => WalletAdjustment::with([
                'user'     => function ($query) {
                    $query->select('id', 'uuid', 'first_name', 'last_name');
                },
                'adjuster' => function ($query) {
                    $query->select('id', 'uuid', 'first_name', 'last_name');
                },
            ])
                ->when($request->filled('filters.search'), function ($q) use ($request) {
                    return $q->where('number', $request->filters['search'])
                        ->orWhere('transactionable_type', 'like', '%' . $request->filters['search'] . '%');
                })
                ->orderBy('id', 'DESC')
                ->paginate(config('app.pagination.per_page'))
                ->onEachSide(2)
                ->withQueryString(),
        ]);
    }

    /**     
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show(WalletAdjustment $walletAdjustment)
    {
        $walletAdjustment->load([
            'user'     => function ($query) {
                $query->select('id', 'uuid', 'first_name', 'last_name');
            },
            'adjuster' => function ($query) {
                $query->select('id', 'uuid', 'first_name', 'last_name');
            },
        ]);

        return inertia('Admin/Transactions/WalletAdjustments/Show', [
            'wallet_adjustment' => $walletAdjustment,
            'data'              => [
                'title' => __('Wallet Adjustment') . ' ' . $walletAdjustment->number,

            ],
        ]);
    }

}
