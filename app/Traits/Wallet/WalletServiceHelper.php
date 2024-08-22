<?php
namespace App\Traits\Wallet;

use App\Models\NumberGenerator;
use App\Models\Wallets\Wallet;

trait WalletServiceHelper
{
    private function add($amount, $type, $transactionalModel)
    {
        $wallet          = $this->wallet();
        $wallet->balance = $wallet->balance + $amount;
        $wallet->save();

        $transactionalModel->walletTransactions()->attach($wallet->id, [
            'number'  => NumberGenerator::gen('App\Models\Wallets\Wallet'),
            'type'    => strtolower($type),
            'amount'  => $amount,
            'balance' => $wallet->balance,
        ]);

        return $wallet;
    }

    private function deduct($amount, $type, $transactionalModel)
    {
        $wallet          = $this->wallet();
        $wallet->balance = $wallet->balance - $amount;
        $wallet->save();

        $transactionalModel->walletTransactions()->attach($wallet->id, [
            'number'  => NumberGenerator::gen('App\Models\Wallets\Wallet'),
            'type'    => strtolower($type),
            'amount'  => -$amount,
            'balance' => $wallet->balance,
        ]);

        return $wallet;
    }

    private function wallet()
    {
        return Wallet::firstOrCreate(['user_id' => $this->model->id], [
            'user_id' => $this->model->id,
            'balance' => 0,
        ]);
    }

}
