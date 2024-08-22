<?php

namespace Database\Seeders\Dummy;

use App\Models\ProjectManagement\Task;
use App\Models\ProjectManagement\TaskContent;
use Database\Seeders\Dummy\TaskSeederHelper\GenerateTask;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
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
        Task::reguard();

        (new GenerateTask)->taskWithPreviousDate();
        (new GenerateTask)->regular();

        $tasks = Task::whereNotNull('task_status_id')->get();

        foreach ($tasks as $task) {
            $text = $this->faker->paragraphs(3, true);
            $text .= '<p>' . $this->faker->text($this->faker->randomElement(range(300, 500))) . '</p>';
            $text .= '<p>' . $this->faker->text($this->faker->randomElement(range(300, 500))) . '</p>';
            $content = new TaskContent([
                'title'   => $this->faker->sentence(),
                'content' => $text,
            ]);
            $task->content()->save($content);
        }

    }

}
