<?php

namespace Database\Seeders\Dummy;

use App\Models\Business\AuthorLevel;
use Illuminate\Database\Seeder;

class AuthorLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $records = [
            [
                'name'                      => 'Standard',
                'description'               => 'One of our authors, proficient in your subject area, ready to meet your deadline',
                'is_popular'                => NULL,
                'numeric_value'             => 1,
                'percentage'                => 0,
                // 'price_per_word_editing'      => 0.005,
                // 'price_per_word_proofreading' => 0.003,
                'number_of_tasks_at_a_time' => 3,

            ],
            [
                'name'                      => 'Premium',
                'description'               => 'High-rank professional with extensive experience in your field of study',
                'is_popular'                => TRUE,
                'numeric_value'             => 2,
                'percentage'                => 10,
                // 'price_per_word_editing'      => 0.006,
                // 'price_per_word_proofreading' => 0.005,
                'number_of_tasks_at_a_time' => 5,
            ],
            [
                'name'                      => 'Expert',
                'numeric_value'             => 3,
                'description'               => 'One of the top 10 experts with the highest customer appraisal in your subject',
                'is_popular'                => NULL,
                'percentage'                => 15,
                // 'price_per_word_editing'      => 0.005,
                // 'price_per_word_proofreading' => 0.003,
                'number_of_tasks_at_a_time' => -1,
            ],

        ];
        AuthorLevel::unguard();

        foreach ($records as $row) {
            AuthorLevel::create($row);
        }

    }
}
