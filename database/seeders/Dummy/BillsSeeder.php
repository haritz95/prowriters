<?php

namespace Database\Seeders\Dummy;

use App\Models\Billing\Bill;
use App\Models\Billing\BillItem;
use App\Models\Author\AuthorProfile;
use App\Models\NumberGenerator;
use App\Models\ProjectManagement\Task;
use App\Models\ProjectManagement\TaskStatus;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BillsSeeder extends Seeder
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
        $author = User::find(3);

        $profile = AuthorProfile::where('user_id', $author->id)
            ->select(['address', 'city', 'state'])->get()->first();

        $taskCollection = Task::whereNotNull('is_archived_for_admin')
            ->select(['id', 'author_payment_amount'])->where('task_status_id', TaskStatus::COMPLETE)->get()->chunk(3);

        foreach ($taskCollection as $index => $tasks) {

            $data['uuid']              = Str::orderedUuid();
            $data['author_id']     = $author->id;
            $data['name']              = $author->full_name;
            $data['address']           = $profile->address;
            $data['note']              = $this->faker->text;
            $data['invoice_number']    = rand();
            $data['total']             = $tasks->sum('author_payment_amount');
            $data['number']            = NumberGenerator::gen(Bill::class);
            $data['paid']              = now();
            $data['payment_reference'] = rand();
            $data['paid_by_user_id']   = 1; // Admin

            // Finally create the bill
            $bill = Bill::create($data);
            
            foreach ($tasks as $task) {

                $item = new BillItem([
                    'task_id' => $task->id,
                    'total'   => $task->author_payment_amount,

                ]);

                // Save the billing items
                $bill->items()->save($item);

                // Update task
                $task->is_billed = true;
                $task->save();
            }

        }

    }
}
