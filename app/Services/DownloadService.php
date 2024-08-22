<?php

namespace App\Services;

use App\Models\Accounting\Invoice;
use App\Models\Payments\Payment;
use Barryvdh\DomPDF\Facade\Pdf;

class DownloadService
{
    public function paymentReceipt(Payment $payment)
    {
        $pdf = Pdf::loadView('download.payment_receipt', [
            'payment' => $payment,
            'company' => [
                'name'    => get_company_name(),
                'address' => get_company_address(),
            ],

        ]);

        return $pdf->download('payment_receipt_' . $payment->number . '.pdf');
    }

    public function invoice(Invoice $invoice)
    {
        $invoice->load(['items', 'customer', 'status', 'items.invoiceable' => function ($q) {
            $q->select(['id', 'number']);
        }]);

        $pdf = Pdf::loadView('download.invoice', [
            'company' => [
                'name'    => get_company_name(),
                'address' => get_company_address(),
                'logo'    => get_company_logo(),
            ],

            'invoice'           => $invoice,
            'invoiceable_types' => [
                'task' => __('Task'),
            ],
        ]);
        return $pdf->download('invoice_' . $invoice->number . '.pdf');
        // return $pdf->stream('invoice.pdf');
    }
}
