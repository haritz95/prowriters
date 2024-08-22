<?php

namespace App\Enums;

abstract class InvoiceStatusType
{
    const UNPAID         = 1;
    const PAID           = 2;
    const PARTIALLY_PAID = 3;
    const OVERDUE        = 4;
    const FORWARDED      = 5; 
}
