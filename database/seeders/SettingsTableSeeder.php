<?php

namespace Database\Seeders;

use App\Enums\OperationType;
use App\Models\Business\Urgency;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsTableSeeder extends Seeder
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
        DB::table('settings')->truncate();

        $logo = 'storage/photos/1/logo.png';

        // $logo2 = 'public/uploads/logo.png';

        // Settings
        $settings = [
            'prowriters_version' => get_software_version(),
            'company_name'       => 'ProWriters',
            'company_logo'       => $logo,

            'company_phone'              => '541 754-3010',
            'company_address'            => $this->faker->address,
            'company_country'            => 'USA',
            'company_email'              => 'support@prowriters.com',
            'company_notification_email' => 'support@prowriters.com',

            // 'footer_text'                       => 'All rights reserved | Microelephant',
            'system_starting_year' => date("Y"),
            'time_zone'            => 'America/New_York',
            // Google Recaptcha
            'recaptcha_site_key'   => null,
            'recaptcha_secret'     => null,
            'recaptcha_enable'     => null,

            // Google tag manager
            'google_tag_id'         => '',
            // Currency
            'decimal_symbol'        => '.',
            'thousand_separator'    => ',',
            'digit_grouping_method' => '1',
            'currency_symbol'       => '$',
            'currency_code'         => 'USD',
            'currency_position'     => 'before_amount',
            'currency_precision'    => 2,

            // 'enable_browsing_work'  => 'yes',

            'business_operation_type'      => OperationType::COMBINED,
            // 'number_of_revision_allowed'   => '-1',
            'payout_amount_threshold'      => 20,
            'dead_line_extension_by_value' => 24,
            'dead_line_extension_by_type'  => Urgency::TYPE_HOURS,
            'disable_quality_control'      => 'no',
            // 'commission_rate_from_bid'     => 20,
            // Sales Tax
            'enable_sales_tax'             => true,
            'enable_self_assigning_tasks'  => true,
            'sales_tax_rate'               => '6',

            // Website UI
            'website_logo'                 => $logo,
            'website_accept_payment_image' => 'storage/photos/1/we_accept.png',
            'website_hero_image'           => 'storage/photos/1/homepage/hero.png',
            'website_hero_image_position'  => 'right',

            'facebook'  => 'http://www.facebook.com/microelephant',
            'twitter'   => 'http://www.twitter.com',
            'instagram' => 'http://www.instagram.com',
            'linkedin'  => 'http://www.linkedin.com',
            'youtube'   => 'http://www.youtube.com',

            'default_receipt_id_for_incoming_messages' => 1, // Admin

        ];

        foreach ($settings as $key => $value) {
            DB::table('settings')->insert(['option_key' => $key, 'option_value' => $value]);
        }

    }

}
