<?php

namespace App\Models\Wallets;

use App\Traits\Wallet\WalletTransactionTrait;
use Illuminate\Database\Eloquent\Model;


class WalletAdjustment extends Model
{
    use WalletTransactionTrait;

    protected $fillable = [
        'id',
        'uuid',
        'number',
        'type',
        'amount',
        'description',
        'user_id',
        'adjuster_id',
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

    function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    function adjuster()
    {
        return $this->belongsTo('App\Models\User', 'adjuster_id', 'id');
    }
}
