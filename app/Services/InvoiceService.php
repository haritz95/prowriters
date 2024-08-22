<?php

namespace App\Services;

use App\Enums\InvoiceStatusType;
use App\Models\Accounting\Invoice;
use App\Models\NumberGenerator;
use Illuminate\Support\Str;

class InvoiceService
{

    public function create(array $data, array $invoice_line_items): Invoice
    {
        $data               = $data;
        $invoice_line_items = $invoice_line_items;

        $data['uuid']   = Str::orderedUuid();
        $data['number'] = NumberGenerator::gen(Invoice::class);

        $invoice = Invoice::create($data);

        foreach ($invoice_line_items as $item) {

            $invoice->items()->create($item);
            if (isset($item['invoiceable_model']) && $item['invoiceable_model']) {
                $item['invoiceable_model']->invoice_id = $invoice->id;
                $item['invoiceable_model']->save();
            }
        }

        return $invoice;
    }

    public function adjustBalanceInInvoice($invoice_id, $amount): Invoice
    {
        $invoice = Invoice::find($invoice_id);
        return $this->adjustBalance($invoice, $amount);
    }

    public function adjustBalanceAndPayFromWallet(Invoice $invoice, $amount)
    {
        $this->adjustBalance($invoice, $amount);

        $invoice->customer->wallet()->pay($amount, $invoice);
    }

    private function adjustBalance(Invoice $invoice, $amount): Invoice
    {
        $new_amount_paid = $invoice->amount_paid + $amount;
        $columns         = [
            'amount_paid'       => $new_amount_paid,
            'invoice_status_id' => InvoiceStatusType::PARTIALLY_PAID,
        ];
        if ($new_amount_paid >= $invoice->total) {
            $columns['invoice_status_id'] = InvoiceStatusType::PAID;
        }
        $invoice->fill($columns)->save();

        return $invoice;
    }
}
