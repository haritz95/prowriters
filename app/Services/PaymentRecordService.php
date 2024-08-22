<?php

namespace App\Services;

use App\Models\NumberGenerator;
use App\Models\Payments\OfflinePaymentMethod;
use App\Models\Payments\Payment;
use App\Models\Payments\PendingForApprovalPayment;
use App\Services\AttachmentService;
use Illuminate\Support\Str;

class PaymentRecordService
{
    /*
    PaymentRecordService records payments in payments table and the balance
    are added to the wallets table and transaction log in wallet_transactions table
     */

    public function store($customer_id, string $paymentMethod, $amount, $transactionReference, $files = null, $date = null, $internal_note = null, $user_id = null): Payment
    {
        if (empty($date)) {
            $date = now();
        }
        $payment = Payment::create([
            'uuid'          => Str::orderedUuid(),
            'number'        => NumberGenerator::gen(Payment::class),
            'customer_id'   => $customer_id,
            'method'        => $paymentMethod,
            'amount'        => $amount,
            'reference'     => $transactionReference,
            'date'          => $date,
            'internal_note' => $internal_note,            
            'user_id'       => $user_id,
        ]);
        $payment->from->wallet()->deposit($payment->amount, $payment);

        if ($files) {
            (new AttachmentService($payment, $files, $customer_id))->save();
        }

        return $payment;
    }

    public function storeOfflinePayment(OfflinePaymentMethod $payment_method, $customer_id, $amount, $reference, $files): PendingForApprovalPayment
    {
        $offline_payment = PendingForApprovalPayment::create([
            'uuid'                      => Str::orderedUuid(),
            'number'                    => NumberGenerator::gen(PendingForApprovalPayment::class),
            'customer_id'               => $customer_id,
            'offline_payment_method_id' => $payment_method->id,
            'amount'                    => $amount,
            'reference'                 => $reference,
        ]);

        if ($payment_method->settings->requires_uploading_attachment == true) {
            (new AttachmentService($offline_payment, $files, $customer_id))->save();
        }
        return $offline_payment;
    }
}
