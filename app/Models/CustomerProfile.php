<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Laravel\Cashier\Billable;

class CustomerProfile extends Model
{
    use Billable;

    public $timestamps = false;

    public $fillable = [
        'internal_note',
        'allow_paying_later',
        // 'credit_limit',

    ];

    /**
     * Get the customer name that should be synced to Stripe.
     */
    public function stripeName(): string | null
    {
        return $this->basicInformation->full_name;
    }

    /**
     * Get the customer email that should be synced to Stripe.
     */
    public function stripeEmail(): string | null
    {
        return $this->basicInformation->email;
    }

    public function basicInformation()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

}
