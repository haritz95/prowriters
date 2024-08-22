<?php
namespace App\Models\Billing;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{

    protected $fillable = [
        'uuid',
        'service_type',
        'stripe_id',
        'title',
        'description',
        'price',
        'number_of_characters_allowed_per_month',
        'is_free',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_free' => 'boolean',
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

}
