<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wallets\WalletTransaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return inertia('Admin/Transactions/Wallets', [
            'data'         => [
                'title' => __('Wallet Transactions'),
                'urls'  => [
                    'search' => route('admin.transactions.index'),
                ],
            ],
            'filters'      => $request->only('filters'),
            'transactions' => WalletTransaction::with([
                'wallet',
                'wallet.user' => function ($query) {
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
                ->withQueryString()
                ->through(fn($transaction) => [
                    'date'              => $transaction->created_at,
                    'number'            => $transaction->number,
                    'type'              => $transaction->type,
                    'user'              => $transaction->wallet->user->full_name,
                    'user_profile_link' => route('admin.customers.show', $transaction->wallet->user->uuid),

                    'transactionable_type' => WalletTransaction::translateJargon($transaction->transactionable_type),
                    'description'          => $transaction->relatedTable->number,
                    'reference'            => optional($transaction->relatedTable)->number,
                    'reference_link'       => WalletTransaction::getReferenceLinkForAdmin($transaction),
                    'amount'               => format_currency($transaction->amount),

                ]),
        ]);
    }

}
