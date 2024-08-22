<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\UsersSeeder;
use Database\Seeders\ServiceSeeder;
use Database\Seeders\TaskStatusSeeder;
use Illuminate\Support\Facades\Schema;
use Database\Seeders\Website\FaqSeeder;
use Database\Seeders\ServiceLevelSeeder;
use Database\Seeders\InvoiceStatusSeeder;
use Database\Seeders\SettingsTableSeeder;
use Database\Seeders\CountriesTableSeeder;
use Database\Seeders\EducationLevelSeeder;
use Database\Seeders\SystemLanguageSeeder;
use Database\Seeders\ApplicantStatusesSeeder;
use Database\Seeders\Website\WebsiteMenuSeeder;
use Database\Seeders\Website\WebsitePageSeeder;
use Database\Seeders\Website\HomepageSectionSeeder;
use Database\Seeders\Website\WebsiteTestimonialSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        $this->call([
            SystemLanguageSeeder::class,
            SettingsTableSeeder::class,
            ApplicantStatusesSeeder::class,
            CountriesTableSeeder::class,
            ServiceSeeder::class,
            InvoiceStatusSeeder::class,
            TaskStatusSeeder::class,
            EducationLevelSeeder::class,
            ServiceLevelSeeder::class,
            HomepageSectionSeeder::class,
            WebsiteTestimonialSeeder::class,
            FaqSeeder::class,
            WebsitePageSeeder::class,
            WebsiteMenuSeeder::class,
            UsersSeeder::class,
            
        ]);
        Schema::enableForeignKeyConstraints();
    }
}
