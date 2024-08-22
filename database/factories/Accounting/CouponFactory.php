<?php

namespace Database\Factories\Accounting;

use App\Models\User;
use App\Enums\CouponType;
use App\Models\Accounting\Coupon;
use Illuminate\Database\Eloquent\Factories\Factory;

class CouponFactory extends Factory
{

    protected $model = Coupon::class;

    // FirstOrder
    // Percentage
    // Fixed
    // Date Expired
    // Usage Limit Crossed
    //

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            // 'type',
            // 'code',
            // 'description',
            // 'amount',
            'active_date' => now(),
            'expiry_date' => null,
            // 'minimum_spend',
            // 'maximum_discount',
            'usage_limit_per_coupon' => null,
            'usage_limit_per_user'   => null,
            // 'specific_customer_only',
            // 'customer_id',
            // 'first_order_only',
            'inactive'    => null,
            'archive'     => null,
            'user_id'     => 1, // Admin
        ];
    }

    public function percentage()
    {
        return $this->state(function (array $attributes) {
            return [
                'type'                   => CouponType::Percentage,
                'code'                   => 'percentage_'. rand(1000, 9999),
                'description'            => 'Percentage Example',
                'amount'                 => 1.5,
                'minimum_spend'          => 10,
                'maximum_discount'       => 5,                
            ];
        });
    }

    public function fixed()
    {
        return $this->state(function (array $attributes) {
            return [
                'type'                   => CouponType::Fixed,
                'code'                   => 'fixed_price_'. rand(1000, 9999),
                'description'            => 'Flat discount example',
                'amount'                 => 2,
                'minimum_spend'          => 10,
                'maximum_discount'       => null,                

            ];
        });
    }

    public function specificCustomerOnly()
    {
        return $this->state(function (array $attributes) {
            return [
                'specific_customer_only' => true,
                'customer_id'            => User::customers()->orderBy('id', 'ASC')->get()->first()->id,
            ];
        });
    }

    public function firstOrderOnly()
    {
        return $this->state(function (array $attributes) {
            return [
                'first_order_only'       => true,
                'usage_limit_per_coupon' => null,
                'usage_limit_per_user'   => null,
            ];
        });
    }

    public function dateExpired()
    {
        return $this->state(function (array $attributes) {
            return [
                'active_date' => now()->subDays(10),
                'expiry_date' => now()->subDays(5),
            ];
        });
    }
    
    public function addExpiryDate()
    {
        return $this->state(function (array $attributes) {
            return [
                'expiry_date' => now()->addDays(10),
            ];
        });
    }

}
