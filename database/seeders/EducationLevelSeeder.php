<?php

namespace Database\Seeders;


use App\Models\Author\EducationLevel;
use Illuminate\Database\Seeder;

class EducationLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EducationLevel::insert([
            [
                'name' => 'High School',
            ],
            [
                'name' => 'Some College',
            ],
            [
                'name' => 'Associate',
            ],
            [
                'name' => 'Bachelors',
            ],
            [
                'name' => 'Masters',
            ],
            [
                'name' => 'Doctorate',
            ],

        ]);
    }
}
