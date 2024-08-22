<?php
namespace Database\Seeders\Dummy;

use App\Models\Payments\OfflinePaymentMethod;
use App\Services\PaymentRecordService;
use Illuminate\Database\Seeder;

class PendingForApprovalPaymentSeeder extends Seeder
{
    public $faker;

    private $customer_id = 2;

    public function __construct()
    {
        $this->faker = \Faker\Factory::create();
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $files = $this->getAttachments();

        $payment_methods = OfflinePaymentMethod::all();

        foreach ($payment_methods as $payment_method) {

            for ($i = 0; $i <= 5; $i++) {
                $payment = new PaymentRecordService();
                $payment->storeOfflinePayment($payment_method, $this->customer_id, $this->faker->randomFloat(2, 10, 100), $this->faker->ean13, $files);
            }
        }

    }

    private function getAttachments()
    {
        $target_path = 'public/attachments/';

        return [
            [
                'name'         => $target_path . 'Dummy_attachment.txt',
                'display_name' => 'Dummy Attachment',
            ],
        ];
    }
}
