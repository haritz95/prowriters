<?php

namespace App\Console\Commands;

use App\Models\Setting;
use Illuminate\Console\Command;
use App\Services\FilesSeederService;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Event;
use App\Models\Payments\PaymentGateway;
use Illuminate\Support\Facades\Storage;
use Jackiedo\DotenvEditor\Facades\DotenvEditor;

class CreateDummyData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'prowriters:dummy-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */

    public function handle()
    {
        // Disable Events
        $events = Event::getRawListeners();
        foreach ($events as $event_name => $closure) {
            Event::forget($event_name);
        }

        //File::cleanDirectory(Storage::path('app/public/attachments/'));
        
        (new FilesSeederService())->generate();

        \Artisan::call("db:wipe");
        \Artisan::call("migrate");

        $this->disableEmailSending();
        \Artisan::call("db:seed --class=DatabaseSeeder");
        \Artisan::call("db:seed --class=DummySeeder");

        $this->email();
        $this->dummySettings();
        // \Artisan::call("route:clear");
        // \Artisan::call("cache:clear");
        // \Artisan::call("config:clear");
        // \Artisan::call("view:clear");
        // \Artisan::call("migrate:reset");
        // \Artisan::call("migrate");
        // \Artisan::call("db:seed");

        $this->paymentGateways();
        \Artisan::call("cache:clear");
        // the driver will send fake emails
        // config()->set('mail', array_merge(config('mail'), [
        //     'driver' => 'log',
        // ]));
        /*
    \Artisan::call("db:seed --class=AdditionalServicesTableSeeder");
    \Artisan::call("db:seed --class=ServicesTableSeeder");
    \Artisan::call("db:seed --class=UrgenciesTableSeeder");
    \Artisan::call("db:seed --class=WorkLevelsTableSeeder");

    \Artisan::call("db:seed --class=DummyUserSeeder");
    \Artisan::call("db:seed --class=OrderTableSeeder");
    \Artisan::call("db:seed --class=BillsTableSeeder");

    $this->paymentGateways();

    \Artisan::call("db:seed --class=OfflinePaymentMethodTableSeeder");
    \Artisan::call("db:seed --class=PendingForApprovalPaymentTableSeeder");
    \Artisan::call("db:seed --class=ApplicantsTableSeeder");
    \Artisan::call("db:seed --class=RecruitmentSettingsSeeder");
    \Artisan::call("db:seed --class=WalletBalanceSeeder");

    // Mailchimp
    // 'apiKey' => '07a2d8b9a3795cb3c0dc8bf90527f1e9-us6',
    // 'server' => 'us6'
    // ListID: 24c9e18c9b

    // 'google' => [
    //     'client_id' => '67648706504-ts5hp20lotej6qoq8f60htjbo1csfd7b.apps.googleusercontent.com',
    //     'client_secret' => 'NDX5Cbjd4vPSVINOYeNAl9JT',
    //     'redirect' => 'http://127.0.0.1:8000/auth/google/callback',
    //     //'redirect' => route('handle_google_auth_callback'),
    // ],

    // 'facebook' => [
    //     'client_id' => '1103615136792622',
    //     'client_secret' => '71d22666897f51dc91bd537f5d7a158d',
    //     'redirect' => 'http://localhost:8000/auth/facebook/callback',
    //     //'redirect' => route('handle_google_auth_callback'),
    // ],

    // 'twitter' => [
    //     'client_id' => 'KLwyTxBqtD4MuhpFHwnpAOf1I',
    //     'client_secret' => '7w30RXUprBIYVv324hpWUWmwCaAsPSwNdXmTAfPOddH7tShUPn',
    //     'redirect' => 'http://localhost:8000/auth/twitter/callback',
    //     //'redirect' => route('handle_twitter_auth_callback'),
    // ],

    // 'linkedin' => [
    //     'client_id' => '78ck9hzbjxa33l',
    //     'client_secret' => 'jejf67jXU4nJTt8p',
    //     'redirect' => 'http://localhost:8000/auth/linkedin/callback',
    //     //'redirect' => route('handle_linkedin_auth_callback'),
    // ],

     */
    }

    private function dummySettings()
    {
        $records = [
            ['option_key' => 'google_client_id', 'option_value' => '67648706504-ts5hp20lotej6qoq8f60htjbso1csfd7b.apps.googleusercontent.com', 'auto_load_disabled' => true],
            ['option_key' => 'google_client_secret', 'option_value' => 'NDX5Cbjd4svPSVINOYeNAl9JT', 'auto_load_disabled' => true],
            ['option_key' => 'is_google_enable', 'option_value' => 1, 'auto_load_disabled' => true],

            // Google reCaptcha
            ['option_key' => 'recaptcha_site_key', 'option_value' => '6Lf1unoUAAAAAEwmAGca8IXWcesNqF_usErQcgxBR', 'auto_load_disabled' => false],
            ['option_key' => 'recaptcha_secret', 'option_value' => '6Lf1unoUAAAAAM2ciYycllukRsnzk9dFPqMDfZ_lk', 'auto_load_disabled' => false],
            ['option_key' => 'recaptcha_enable', 'option_value' => null, 'auto_load_disabled' => false],

            ['option_key' => 'tinymce_key', 'option_value' => '30pbmddzz9fs13mm4afrxvkub26sdcmse0te7i0szopzuaeaz', 'auto_load_disabled' => false],
        ];

        foreach ($records as $row) {
            $settings = Setting::where('option_key', $row['option_key'])->get();
            if ($settings->count() > 0) {
                $setting                     = $settings->first();
                $setting->option_value       = $row['option_value'];
                $setting->auto_load_disabled = $row['auto_load_disabled'];
                $setting->save();
            } else {
                Setting::create($row);
            }
        }
        // Setting::insert([
        //     ['option_key' => 'google_client_id', 'option_value' => '67648706504-ts5hp20lotej6qoq8f60htjbo1csfd7b.apps.googleusercontent.com', 'auto_load_disabled' => true],
        //     ['option_key' => 'google_client_secret', 'option_value' => 'NDX5Cbjd4vPSVINOYeNAl9JT', 'auto_load_disabled' => true],
        //     ['option_key' => 'is_google_enable', 'option_value' => 1, 'auto_load_disabled' => true],

        //     // Google reCaptcha
        //     ['option_key' => 'recaptcha_site_key', 'option_value' => '6Lf1unoUAAAAAEwmAGca8IXWceNqF_usErQcgxBR', 'auto_load_disabled' => false],
        //     ['option_key' => 'recaptcha_secret', 'option_value' => '6Lf1unoUAAAAAM2ciYycllukRnzk9dFPqMDfZ_lk', 'auto_load_disabled' => false],
        //     ['option_key' => 'recaptcha_enable', 'option_value' => TRUE, 'auto_load_disabled' => false],
        // ]);

    }
    private function paymentGateways()
    {
        PaymentGateway::insert([
            // Paypal Checkout
            [
                'unique_name' => 'paypal_checkout',
                'name'        => 'Paypal Smart Checkout',
                'keys'        => json_encode([
                    // 'client_id' => "ASurpZzLelJpjJCwFSCaStoV71rjInqmmEWkDn2mWk8bxGVZiUgW_Y59tWRCFyx-no7AUW8ozjzGb6Cc",
                    // 'client_secret' => "EDta8P64QXuKQYoD8GwhlNaROaySGai0pYwoJXGzBjCBc-5BZ6Ud_pgBQmlWb6WQyFMQvhjJh6noxsqh",

                    'client_id'     => "AZyzyBPD5pLBCpmSvH7piN4o3P9dZ8_ehrNs19ZEi0f0N4lJPuSt1BvwxM-LvF7BRQEpkTL0CUwORHyod",
                    'client_secret' => "EGgRkyE7TxNCjMfMgiZakmfje8hHknL4XMmsb-6O0mM9MnWIOvCvTiJyM0pjv08NFAQw_jILdGgjR1eP_",
                    'environment'   => 'sandbox',
                ]),

            ],
            // Braintree
            [
                'unique_name' => 'braintree',
                'name'        => 'Braintree',
                'keys'        => json_encode([
                    'merchant_id'       => 'z5sjjhbgrbzfgnw6',
                    'public_key'        => 'n6srpqmqn6mq5sdv',
                    'private_key'       => '59f9e4bf4b149f54fa5s53fb9c7f7c137',
                    'is_paypal_enabled' => true,
                    'environment'       => 'sandbox',
                ]),

            ],
            // Stipe
            [
                'unique_name' => 'stripe',
                'name'        => 'Stripe',
                'keys'        => json_encode([
                    'publishable_key' => 'pk_test_JBnqGXZs3sHVpaR4bBwPFXoTm',
                    'secret_key'      => 'sk_test_9rRMThBsLosdJuBIbTIVuP4Q',
                ]),

            ],

        ]);
    }

    private function disableEmailSending()
    {
        // Email has been disabled from settings, so changing the driver will send fake emails
        config()->set('mail', array_merge(config('mail'), [
            'driver' => 'log',
        ]));
    }

    private function email()
    {
        DotenvEditor::setKeys([
            ['key' => 'MAIL_MAILER', 'value' => 'smtp'],
            ['key' => 'MAIL_HOST', 'value' => 'smtp.mailtrap.io'],
            ['key' => 'MAIL_PORT', 'value' => '2525'],
            ['key' => 'MAIL_USERNAME', 'value' => 'ab2e68188741d2'],
            ['key' => 'MAIL_PASSWORD', 'value' => '0b3bf07bd5204f'],
            ['key' => 'MAIL_ENCRYPTION', 'value' => 'tls'],
            ['key' => 'MAIL_FROM_ADDRESS', 'value' => 'prowriters@microelephant.io'],
            ['key' => 'MAILGUN_DOMAIN', 'value' => 'company_email_mailgun_domain'],
            ['key' => 'MAILGUN_SECRET', 'value' => 'company_email_mailgun_key'],
            ['key' => 'QUEUE_CONNECTION', 'value' => 'sync'],
        ]);
        DotenvEditor::save();
    }

    private function initial_env_setup()
    {
        // If database connection is alright, update the ENV file.
        DotenvEditor::setKeys([
            [
                'key'   => 'APP_DEBUG',
                'value' => 'TRUE',

            ],
            [
                'key'   => 'APP_ENV',
                'value' => 'development',

            ],

        ]);

        DotenvEditor::save();

        return true;
    }

    private function finalize_env_setup()
    {
        // If database connection is alright, update the ENV file.
        DotenvEditor::setKeys([
            [
                'key'   => 'APP_ENV',
                'value' => 'production',

            ],
            [
                'key'   => 'APP_DEBUG',
                'value' => 'FALSE',

            ],

        ]);

        DotenvEditor::save();

        return true;
    }
}
