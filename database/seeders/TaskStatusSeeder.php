<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProjectManagement\TaskStatus;



class TaskStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TaskStatus::insert(TaskStatus::getList());
    }
}
