<?php

namespace Database\Seeders;

use Database\Seeders\Dummy\AcademicLevelsTableSeeder;
use Database\Seeders\Dummy\AdditionalServiceSeeder;
use Database\Seeders\Dummy\AnnouncementSeeder;
use Database\Seeders\Dummy\ApplicantSeeder;
use Database\Seeders\Dummy\AssignmentSeeder;
use Database\Seeders\Dummy\AuthorLevelSeeder;
use Database\Seeders\Dummy\BidRequestSeeder;
use Database\Seeders\Dummy\BillsSeeder;
use Database\Seeders\Dummy\BlogSeeder;
use Database\Seeders\Dummy\CouponSeeder;
use Database\Seeders\Dummy\GrammaticalPeopleSeeder;
use Database\Seeders\Dummy\LanguageTableSeeder;
use Database\Seeders\Dummy\MessageSeeder;
use Database\Seeders\Dummy\OfflinePaymentMethodSeeder;
use Database\Seeders\Dummy\PaperFormatSeeder;
use Database\Seeders\Dummy\PendingForApprovalPaymentSeeder;
use Database\Seeders\Dummy\SubjectTableSeeder;
use Database\Seeders\Dummy\TaskSeeder;
use Database\Seeders\Dummy\UrgenciesTableSeeder;
use Database\Seeders\Dummy\UsersTableSeeder;
use Database\Seeders\Dummy\WalletBalanceSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DummySeeder extends Seeder
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

            OfflinePaymentMethodSeeder::class,

            UrgenciesTableSeeder::class,
            AcademicLevelsTableSeeder::class,
            SubjectTableSeeder::class,
            AdditionalServiceSeeder::class,
            PaperFormatSeeder::class,
            AuthorLevelSeeder::class,

            AssignmentSeeder::class,
            GrammaticalPeopleSeeder::class,
            LanguageTableSeeder::class,

            // User Seeder needs to be after business setup seeders
            UsersTableSeeder::class,
            CouponSeeder::class,
            TaskSeeder::class,
            WalletBalanceSeeder::class,
            PendingForApprovalPaymentSeeder::class,
            BillsSeeder::class,
            ApplicantSeeder::class,
            BlogSeeder::class,
            BidRequestSeeder::class,
            AnnouncementSeeder::class,
            MessageSeeder::class,

        ]);
        Schema::enableForeignKeyConstraints();
    }
}
