<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SystemLanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('system_languages')->insert([
            [
                'iso_code'         => 'en',
                'name'             => 'English',
                'country_code'     => 'us',
                'layout_direction' => 'ltr',
                'is_default'       => true,
            ],
            [
                'iso_code'         => 'de',
                'name'             => 'German',
                'country_code'     => 'de',
                'layout_direction' => 'ltr',
                'is_default'       => null,
            ],
        ]);
    }
}
