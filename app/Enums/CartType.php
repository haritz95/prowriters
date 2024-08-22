<?php

namespace App\Enums;

abstract class CartType
{
    const ORDER               = 'order';
    const LOAD_WALLET         = 'load_wallet';
    const PAYMENT_FOR_INVOICE = 'payment_for_invoice';
    const PAYMENT_FOR_BID     = 'payment_for_bid';

    public static function all()
    {
        return [
            self::ORDER,
            self::LOAD_WALLET,
            self::PAYMENT_FOR_INVOICE,
            self::PAYMENT_FOR_BID,
        ];
    }
}
