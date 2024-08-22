<?php

namespace Database\Seeders\Website;

use App\Models\Website\Faq\FaqCategory;
use App\Models\Website\Faq\FaqQuestion;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{

    private $faker;

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
        $list_of_categories = [
            'Customer Care',
            'Order & Delivery',
            'Pricing & Payment',
            'Quality & Satisfaction',
            'Security & Confidentiality',
            'Writers & Language',
        ];

        foreach ($list_of_categories as $category) {
            $categories_array[] = ['locale' => 'en', 'name' => $category];
        }
        FaqCategory::insert($categories_array);

        $categories = FaqCategory::all();

        foreach ($categories as $category) {
            foreach (range(1, 5) as $key => $value) {
                $faq = FaqQuestion::create([
                    'locale'      => 'en',
                    'title'       => $this->faker->sentence,
                    'description' => $this->faker->realText(500),
                ]);
                $faq->categories()->attach([$category->id]);
            }
        }

    }
}
