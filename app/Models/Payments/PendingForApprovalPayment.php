<?php

namespace App\Models\Payments;

use App\Models\Payments\OfflinePaymentMethod;
use App\Models\User;
use App\Traits\HasAttachment;
use App\Traits\Wallet\WalletTransactionTrait;
use Illuminate\Database\Eloquent\Model;

class PendingForApprovalPayment extends Model
{
    use WalletTransactionTrait, HasAttachment;

    protected $fillable = [
        'uuid',
        'number',
        'customer_id',
        'offline_payment_method_id',
        'reference',
        'amount',
    ];

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

    public function from()
    {
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }

    public function method()
    {
        return $this->belongsTo(OfflinePaymentMethod::class, 'offline_payment_method_id', 'id');
    }
}
