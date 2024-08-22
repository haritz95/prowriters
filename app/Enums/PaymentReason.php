<?php

namespace App\Enums;

abstract class PaymentReason
{
    const ORDER             = 'order';
    const WALLET_TOP_UP     = 'wallet_top_up';
}
