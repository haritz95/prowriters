<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCart extends Model
{

    public $fillable = [
        'type',
        'token',
        'user_id',
        'items',
        'sub_total',
        'discount',
        'coupon_id',
        'coupon_code',
        'coupon_discount',
        'sales_tax_rate',
        'sales_tax_amount',
        'total',
        'invoice_id',
        'payment_id',
    ];

    public $casts = [
        'items' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
