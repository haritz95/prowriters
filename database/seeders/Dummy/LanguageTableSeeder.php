<?php

namespace Database\Seeders\Dummy;

use App\Models\Business\Language;
use Illuminate\Database\Seeder;

class LanguageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $languages = [
            [
                'name'       => 'English (United States)',
                'percentage' => 0,
                // 'available_for_content_writing' => TRUE,
                // 'available_for_translation' => TRUE,
            ],
            [
                'name'       => 'English (United Kingdom)',
                'percentage' => 10,
                // 'available_for_content_writing' => TRUE,
                // 'available_for_translation' => TRUE,
            ],
            [
                'name'       => 'English (Canada)',
                'percentage' => 10,
                // 'available_for_content_writing' => TRUE,
                // 'available_for_translation' => TRUE,
            ],
            [
                'name'       => 'German',
                'percentage' => 20,
                // 'available_for_content_writing' => TRUE,
                // 'available_for_translation' => TRUE,
            ],
            [
                'name'       => 'Spanish',
                'percentage' => 20,
                // 'available_for_content_writing' => FALSE,
                // 'available_for_translation' => TRUE,
            ],
        ];

        Language::unguard();
        foreach ($languages as $language) {
            Language::create($language);
        }
    }
}
