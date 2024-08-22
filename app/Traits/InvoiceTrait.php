<?php

namespace App\Traits;

use App\Models\Accounting\Invoice;
use App\Models\Accounting\InvoiceItem;

trait InvoiceTrait
{

    function invoiceItem()
    {
        return $this->morphOne(InvoiceItem::class, 'invoiceable');
       
    }

    function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

}
