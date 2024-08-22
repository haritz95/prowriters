<?php

namespace Database\Seeders\Dummy;

use App\Enums\UserType;
use App\Models\Announcement;
use Illuminate\Database\Seeder;

class AnnouncementSeeder extends Seeder
{

    public $faker;

    public function __construct()
    {

        $this->faker = \Faker\Factory::create();
    }

    public function run()
    {
        Announcement::create([
            'target_user_type' => UserType::AUTHOR,
            'title'            => $this->faker->sentence(),
            'content'          => $this->generateMessage(),
            'user_id'          => 1,
            'inactive'         => null,
        ]);
        Announcement::create([
            'target_user_type' => UserType::AUTHOR,
            'title'            => $this->faker->sentence(),
            'content'          => $this->generateMessage(),
            'user_id'          => 1,
            'inactive'         => null,
        ]);
        Announcement::create([
            'target_user_type' => UserType::AUTHOR,
            'title'            => $this->faker->sentence(),
            'content'          => $this->generateMessage(),
            'user_id'          => 1,
            'inactive'         => null,
        ]);

        Announcement::create([
            'target_user_type' => UserType::AUTHOR,
            'title'            => $this->faker->sentence(),
            'content'          => $this->generateMessage(),
            'user_id'          => 1,
            'inactive'         => null,
        ]);
    }

    private function generateMessage()
    {
        return implode('<br>', $this->faker->paragraphs(3));
    }

}
