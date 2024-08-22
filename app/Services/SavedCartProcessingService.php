<?php

namespace App\Services;

use App\Enums\CartType;
use App\Enums\InvoiceItemType;
use App\Enums\InvoiceStatusType;
use App\Events\NewOrderEvent;
use App\Models\Accounting\Invoice;
use App\Models\ProjectManagement\Task;
use App\Models\User;
use App\Models\UserCart;
use App\Services\CartService;
use App\Services\CouponService;
use App\Services\ProjectManagement\TaskCreateService;

class SavedCartProcessingService
{
    public function onlinePayment(UserCart $userCart, $customer)
    {
        if (isset($userCart->total) && ($userCart->total > 0)) {
            if ($userCart->type == CartType::ORDER) {

                $invoice = $this->createInvoice($userCart, $customer);
                $customer->wallet()->pay($userCart->total, $invoice);

            } elseif ($userCart->type == CartType::PAYMENT_FOR_INVOICE) {

                $invoice = (new InvoiceService())->adjustBalanceInInvoice($userCart->invoice_id, $userCart->total, $customer);
                $customer->wallet()->pay($userCart->total, $invoice);

            } elseif ($userCart->type == CartType::PAYMENT_FOR_BID) {

                $invoice = $this->createInvoice($userCart, $customer);
                $customer->wallet()->pay($userCart->total, $invoice);

            } elseif ($userCart->type == CartType::LOAD_WALLET) {
                // Do nothing
            }
        }

    }

    public function walletPayment(UserCart $userCart, User $customer)
    {
        $invoice = (new InvoiceService())->adjustBalanceInInvoice($userCart->invoice_id, $userCart->total);
        $customer->wallet()->pay($userCart->total, $invoice);

    }

    private function createInvoice(UserCart $userCart, User $customer): Invoice
    {

        foreach ($userCart->items as $item) {
            $item['fields']['customer_id'] = $customer->id;
            $invoice_items[]               = $this->prepareInvoiceItem($item);
        }

        $invoice = (new InvoiceService())->create([
            'invoice_status_id' => InvoiceStatusType::PAID,
            'customer_id'       => $customer->id,
            'sub_total'         => $userCart->sub_total,
            // Discount
            'discount'          => $userCart->discount,
            'coupon_id'         => $userCart->coupon_id,
            'coupon_code'       => $userCart->coupon_code,
            'coupon_discount'   => $userCart->coupon_discount,
            // Sales Tax
            'sales_tax_rate'    => $userCart->sales_tax_rate,
            'sales_tax_amount'  => $userCart->sales_tax_amount,
            // Total
            'total'             => $userCart->total,
            'amount_paid'       => $userCart->total,
            'invoice_date'      => now(),
            'due_date'          => now(),
            'user_id'           => null,
        ], $invoice_items, $customer);

        // Update invoice id in user_carts table
        (app()->make(CartService::class))->updateInvoiceId($userCart->token, $invoice->id);

        // Discount Coupon
        if ($userCart->coupon_id) {
            (app()->make(CouponService::class))->logUsage($userCart->coupon_id);
        }

        //Dispatching Event
        event(new NewOrderEvent($invoice));

        return $invoice;
    }

    /**
     * @param array $item
     *
     * @return array
     */
    public function prepareInvoiceItem(array $item): array
    {
        if ($item['type'] == InvoiceItemType::NEW_TASK) {
            // Create Task
            $invoiceable_model = (new TaskCreateService())($item['fields']);
            // //Dispatching Event
            // $task = $invoiceable_model;
            // event(new NewOrderEvent($task));

        }
        if ($item['type'] == InvoiceItemType::EXISTING_TASK) {
            // Existing Task
            $invoiceable_model = Task::where('id', $item['task_id'])->get()->first();
        }

        return [
            'invoiceable_type'  => strtolower(class_basename(get_class($invoiceable_model))),
            'invoiceable_id'    => $invoiceable_model->id,
            'invoiceable_model' => $invoiceable_model,
            'name'              => $item['name'],
            'description'       => $item['title'],
            'price'             => $item['price'],
            'quantity'          => $item['quantity'],
            'sub_total'         => $item['sub_total'],
        ];
    }
}
