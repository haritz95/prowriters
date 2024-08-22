<?php

namespace App\Models\Accounting;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'code',
        'description',
        'amount',
        'active_date',
        'expiry_date',
        'minimum_spend',
        'maximum_discount',
        'usage_limit_per_coupon',
        'usage_limit_per_user',
        'specific_customer_only',
        'customer_id',
        'first_order_only',
        'inactive',
        'archive',
        'user_id',
    ];

    protected $casts = [
        'first_order_only' => 'boolean',
        'inactive'         => 'boolean',
        'archive'          => 'boolean',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }

    // protected function getActiveDateAttribute($value)
    // {
    //     return convertToLocalTime($value);
    // }

    // protected function getExpiryDateAttribute($value)
    // {
    //     return convertToLocalTime($value);
    // }

    public function scopeActive($query)
    {
        return $query->where('inactive', false)->orWhereNull('inactive');
    }

    // public $preventAttrGet = false;
}
