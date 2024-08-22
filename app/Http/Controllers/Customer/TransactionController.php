<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Wallets\WalletTransaction;
use App\Rules\MoneyFormat;
use App\Services\CartService;
use App\Services\WalletService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{

    public function index(Request $request)
    {
        return inertia('Customer/Transactions/Index', [
            'data'         => [
                'title' => __('Transactions'),
            ],
            'transactions' => WalletTransaction::whereHas('wallet', function ($q) {
                return $q->where('user_id', auth()->user()->id);
            })->orderBy('id', 'DESC')
                ->orderBy('id', 'DESC')
                ->paginate(config('app.pagination.per_page'))
                ->through(fn($transaction) => [
                    'number'               => $transaction->number,
                    'date'                 => $transaction->created_at,
                    'type'                 => $transaction->type,
                    'description'          => $transaction->relatedTable->number,
                    'transactionable_type' => WalletTransaction::translateJargon($transaction->transactionable_type),
                    'amount'               => $transaction->amount,
                    'balance'              => $transaction->balance,
                    'reference_link'       => $this->getReferenceLink($transaction),
                ])
                ->withQueryString(),

        ]);
    }

    public function getReferenceLink($transaction)
    {

        switch ($transaction->transactionable_type) {
            case 'invoice':
                return route('customer.invoices.show', $transaction->relatedTable->uuid);
                break;
            case 'payment':
                return route('customer.payments.show', $transaction->relatedTable->uuid);
                break;
            default:
                return null;
                break;
        }
    }

    public function createFund(Request $request)
    {
        $userWallet = new WalletService(auth()->user());

        return inertia('Customer/Transactions/AddFundToWallet', [
            'data' => [
                'title'   => __('Add funds to your wallet'),
                'balance' => $userWallet->balance(),
            ],
        ]);
    }

    public function storeFund(Request $request, CartService $cartService)
    {
        $request->validate([
            'amount' => ['required', 'min:1', new MoneyFormat],
        ]);

        $cartService->destroySavedCartLoadWallet(auth()->user()->id);

        $token = $cartService->saveCartForLoadWallet(auth()->user()->id, $request->amount);

        return redirect()->route('choose_payment_method', ['token' => $token]);

    }
}
