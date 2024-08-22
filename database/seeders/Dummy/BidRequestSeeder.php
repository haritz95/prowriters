<?php

namespace Database\Seeders\Dummy;

use App\Enums\BidRequestStatusType;
use App\Models\ProjectManagement\Bid;
use App\Models\ProjectManagement\BidRequest;
use App\Models\ProjectManagement\BidRequestStatus;
use Database\Seeders\Dummy\TaskSeederHelper\GenerateTask;
use Illuminate\Database\Seeder;

class BidRequestSeeder extends Seeder
{

    public $faker;

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
        // Bid Request Statuses
        BidRequestStatus::insert(BidRequestStatusType::getList());

        // Create tasks
        $tasks = (new GenerateTask)->createTaskForBidding();

        
        
        // Create bid request for each task
        foreach ($tasks as $task) {
            $task_ids[]    = $task->id;
            $bidRequests[] = BidRequest::create([
                'task_id'               => $task->id,
                'budget'                => $this->faker->numberBetween(20, 250),
                'bid_request_status_id' => BidRequestStatusType::OPEN,
            ]);
        }

        $half = ceil(count($bidRequests) / 2);
        $chunks = array_chunk($bidRequests, $half);
        
        // Create bid for each bid request
        foreach ($chunks[0] as $bidRequest) {
            $this->createBid($bidRequest, 3);
            $this->createBid($bidRequest, 4);

        }

    }

    private function createBid($bidRequest, $author_id)
    {
        $commission_rate = 20;

        $total      = $this->faker->numberBetween(20, $bidRequest->budget);
        $commission = ($total * $commission_rate) / 100;

        Bid::create([
            'total'                    => $total,
            'author_payment_amount'    => $total - $commission,
            'platform_commission_rate' => $commission_rate,
            'bid_request_id'           => $bidRequest->id,
            'author_id'                => $author_id,
            'duration_days'            => $this->faker->numberBetween(5, 10),
            'number_of_revisions'      => $this->faker->numberBetween(1, 5),
        ]);
    }
}
