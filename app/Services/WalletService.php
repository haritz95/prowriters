<?php
namespace App\Services;

use App\Traits\Wallet\WalletServiceHelper;

class WalletService
{
    use WalletServiceHelper;

    private $model;
    private $wallet;

    const TRANSACTION_TYPE_DEPOSIT = 'deposit';
    const TRANSACTION_TYPE_SPENT   = 'spent';
    const TRANSACTION_TYPE_REFUND  = 'refund';
    const TRANSACTION_TYPE_AWARD   = 'award';

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function balance()
    {
        return optional($this->wallet())->balance ?? 0;

    }

    public function id()
    {
        return $this->wallet()->id;
    }

    public function deposit($amount, $transactionalModel)
    {
        return $this->add($amount, self::TRANSACTION_TYPE_DEPOSIT, $transactionalModel);
    }

    public function pay($amount, $transactionalModel)
    {
        return $this->deduct($amount, self::TRANSACTION_TYPE_SPENT, $transactionalModel);
    }

    public function refund($amount, $transactionalModel)
    {
        return $this->add($amount, self::TRANSACTION_TYPE_REFUND, $transactionalModel);
    }

    public function award($amount, $transactionalModel, $description = null)
    {
        return $this->add($amount, self::TRANSACTION_TYPE_AWARD, $transactionalModel);
    }

}
