<?php

namespace Database\Seeders\Dummy;

use App\Models\Accounting\Coupon;
use Illuminate\Database\Seeder;


class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {        

        Coupon::factory()->percentage()->create();
        Coupon::factory()->fixed()->create();
        Coupon::factory()->fixed()->addExpiryDate()->create();
        
        Coupon::factory()->fixed()->specificCustomerOnly()->create();
        Coupon::factory()->percentage()->specificCustomerOnly()->create();
        
        Coupon::factory()->fixed()->firstOrderOnly()->create();
        Coupon::factory()->percentage()->firstOrderOnly()->create();
        
        Coupon::factory()->fixed()->dateExpired()->create();
        Coupon::factory()->percentage()->dateExpired()->create();

      
    }
}
