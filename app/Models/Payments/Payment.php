<?php

namespace App\Models\Payments;

use App\Traits\HasAttachment;
use App\Traits\Wallet\WalletTransactionTrait;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use WalletTransactionTrait, HasAttachment;

    protected $fillable = [
        'uuid',
        'number',
        'user_id',
        'customer_id',
        'method',
        'amount',
        'reference',
        'internal_note',        
        'date',
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

    protected $with = ['from', 'attachments'];

    public function from()
    {
        return $this->belongsTo('App\Models\User', 'customer_id', 'id');
    }
}
