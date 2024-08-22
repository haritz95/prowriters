<?php

namespace Database\Seeders;

use App\Models\Business\ServiceLevel;
use Illuminate\Database\Seeder;

class ServiceLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ServiceLevel::insert([
            [
                'type' => 'fixed',
                'is_default' => true,
                'price' => 0,
                'name' => 'Basic',
                'description' => 'Contact center agents are available 24/7 to answer your questions',
            ],
            [
                'type' => 'fixed',
                'is_default' => false,
                'price' => 5.99,
                'name' => 'Advanced',
                'description' => 'A team of support agents update you on your order progress and communicate with your writer',
            ],
            [
                'type' => 'fixed',
                'is_default' => false,
                'price' => 9.99,
                'name' => 'Premium',
                'description' => 'A dedicated support agent and QA Specialist control your order quality and timely delivery',
            ]

        ]);
    }
}
