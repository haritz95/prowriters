<?php
namespace Database\Seeders\Dummy;

use App\Enums\UserType;
use App\Models\User;
use App\Services\PaymentRecordService;
use Illuminate\Database\Seeder;

class WalletBalanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1 is admin, 3 is customer
        $this->generate(User::where('type', UserType::CUSTOMER)->get()->first()->id);

    }

    public function generate($customer_id, $amount = 3000)
    {
        $faker   = \Faker\Factory::create();
        $payment = new PaymentRecordService();
        $gateway = $faker->randomElement(['Paypal Smart Checkout', 'Stripe']);
        $payment->store($customer_id, $gateway, $amount, $faker->isbn13());
    }
}
