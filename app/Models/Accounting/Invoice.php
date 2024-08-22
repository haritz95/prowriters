<?php

namespace App\Models\Accounting;

use App\Models\Accounting\InvoiceItem;
use App\Models\Accounting\InvoiceStatus;
use App\Models\User;
use App\Traits\Wallet\WalletTransactionTrait;
use App\Traits\WhereDateBetweenTrait;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use WalletTransactionTrait, WhereDateBetweenTrait;
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id',
    ];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }

    protected $fillable = [
        'uuid',
        'number',
        'customer_id',
        'invoice_status_id',
        'sub_total',
        'discount',
        'coupon_id',
        'coupon_code',
        'coupon_discount',
        'sales_tax_rate',
        'sales_tax_amount',
        'total',
        'amount_paid',
        'billing_address',
        'admin_note',
        'customer_note',
        'terms_and_conditions',
        'invoice_date',
        'due_date',
        'user_id',
    ];

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }

    public function status()
    {
        return $this->hasOne(InvoiceStatus::class, 'id', 'invoice_status_id');
    }

    public static function invoiceableTypes()
    {
        return [
            'task' => __('Task'),
        ];
    }
}
