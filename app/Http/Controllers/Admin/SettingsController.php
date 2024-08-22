<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OperationType;
use App\Http\Controllers\Controller;
use App\Mail\TestMail;
use App\Models\Business\Urgency;
use App\Models\Setting;
use App\Models\User;
use App\Models\Website\HomePageSection;
use App\Services\PaymentGatewaySettingsService;
use App\Traits\Settings\AppSettingTrait;
use App\Traits\Settings\CurrencyTrait;
use App\Traits\Settings\GoogleRecaptchaTrait;
use App\Traits\Settings\GoogleTagManagerTrait;
use App\Traits\Settings\RecruitmentSettings;
use App\Traits\Settings\SettingsCRUDTrait;
use App\Traits\Settings\SocialLinkTrait;
use App\Traits\Settings\SocialLoginTrait;
use App\Traits\SystemUpgrade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Mailer\Exception\TransportException;

class SettingsController extends Controller
{
    use SystemUpgrade,
    RecruitmentSettings,
    GoogleRecaptchaTrait,
    SocialLoginTrait,
    CurrencyTrait,
    SettingsCRUDTrait,
    SocialLinkTrait,
    GoogleTagManagerTrait,
        AppSettingTrait;

    public function general()
    {
        return inertia('Admin/Settings/General', [
            'records' => $this->getRecords(array_keys(Setting::generalFields())),
            'data'    => [
                'title' => __('General'),

            ],
        ]);
    }

    public function updateGeneral(Request $request)
    {
        $new_records      = $request->validate(Setting::generalFields());
        $previous_records = $this->getRecords(['company_name', 'hide_website']);
        $this->saveRecords($new_records);
        $this->updateCompanyNameInEnvFile($previous_records, $new_records);

        return redirect()->back()->withSuccess(__('Successfully updated'));
    }

    private function updateCompanyNameInEnvFile($previous_records, $new_records)
    {
        $update_env_keys = false;

        if (isset($previous_records['company_name']) && $previous_records['company_name'] != $new_records['company_name']) {
            $update_env_keys = true;
        }

        if (isset($previous_records['hide_website']) && $previous_records['hide_website'] != $new_records['hide_website']) {
            $update_env_keys = true;

        }

        if ($update_env_keys) {

            $this->updateEnvKeys([
                ['key' => 'APP_NAME', 'value' => $new_records['company_name']],
                ['key' => 'DISABLE_WEBSITE', 'value' => ($new_records['hide_website']) ? TRUE : FALSE],
            ]);
        }

    }

    public function service()
    {
        return inertia('Admin/Settings/Service', [
            'records' => $this->getRecords(array_keys(Setting::serviceFields())),
            'data'    => [
                'title'     => __('Service'),
                'dropdowns' => [
                    'business_operation_types'           => OperationType::get(),
                    'urgency_types'                      => Urgency::types(),
                    'quality_control_availability'       => [
                        ['id' => 'yes', 'name' => __('Yes')],
                        ['id' => 'no', 'name' => __('No')],
                    ],
                    'sales_tax_availability'             => [
                        ['id' => 1, 'name' => __('Yes')],
                        ['id' => '', 'name' => __('No')],
                    ],
                    'find_work_for_authors_availability' => [
                        ['id' => 1, 'name' => __('Yes')],
                        ['id' => '', 'name' => __('No')],
                    ],
                    'admin_users'                        => User::admins()->get(),
                ],
                'urls'      => [
                    'submit_form' => route('admin.settings.service.update'),
                ],

            ],
        ]);
    }

    public function updateService(Request $request)
    {
        $data = $request->validate(Setting::serviceFields(), [

        ], [

            'business_operation_type'      => __('Business Operation Type'),
            'payout_amount_threshold'      => __('Payout Amount Threshold'),
            'dead_line_extension_by_type'  => __('Extend by type'),
            'dead_line_extension_by_value' => __('Extend by value'),
            'disable_quality_control'      => __('Disable Quality Control'),

            'enable_sales_tax'                         => __('Enable Sales Tax'),
            'sales_tax_rate'                           => __('Sales Tax Rate'),
            'enable_self_assigning_tasks'              => __('Allow self assigning tasks'),
            'default_receipt_id_for_incoming_messages' => __('Default receipt for incoming messages from authors'),
        ]);

        if ($data['business_operation_type'] == OperationType::BIDDING) {
            $data['enable_self_assigning_tasks'] = null;
        }

        $this->saveRecords($data);

        return redirect()->back()->withSuccess(__('Successfully updated'));
    }

    public function email()
    {
        foreach (Setting::emailFields() as $key => $value) {

            $rec[$key] = $this->getEnv($value);
        }

        return inertia('Admin/Settings/Email', [
            'data'    => [
                'title'     => __('Email'),
                'dropdowns' => [
                    'queue_connection_options' => [
                        ['id' => 'sync', 'name' => 'Sync'],
                        ['id' => 'database', 'name' => 'Database'],
                    ],
                    'email_sending_options'    => [
                        ['id' => 'smtp', 'name' => __('SMTP')],
                        ['id' => 'mailgun', 'name' => __('Mailgun')],
                        ['id' => 'log', 'name' => __('Turn off email')],
                    ],
                    'email_encryptions'        => [
                        ['id' => '', 'name' => __('None')],
                        ['id' => 'ssl', 'name' => __('SSL')],
                        ['id' => 'tls', 'name' => __('TLS')],
                    ],
                ],
                'urls'      => [
                    'submit_form' => route('admin.settings.email.update'),
                ],
                'tooltips'  => [
                    'smtp_username' => __('Fill only if your email client use username for SMTP login'),
                ],
            ],
            'records' => $rec,
        ]);
    }

    public function updateEmail(Request $request)
    {
        $rules = [];

        if ($request['mail_mailer'] == 'mailgun') {
            $rules = [
                'mailgun_domain'    => 'required',
                'mailgun_secret'    => 'required',
                'mail_from_address' => 'required|email',
            ];
        } else if ($request['mail_mailer'] == 'smtp') {
            $rules = [
                'mail_host'         => 'required',
                'mail_port'         => 'required',
                'mail_from_address' => 'required|email',
                'mail_password'     => 'required',
            ];
        }

        $validator = Validator::make($request->all(), $rules, [
            'mail_host.required'         => 'Host is required',
            'mail_port.required'         => 'Port is required',
            'mail_from_address.required' => 'Email from is required',
            'mail_from_address.email'    => 'Not a valid email address',
            'mail_password.required'     => 'SMTP password is required',
            'mailgun_domain.required'    => 'Mailgun domain is required',
            'mailgun_secret.required'    => 'Mailgun key is required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        foreach (Setting::emailFields() as $input => $envKey) {

            $keys[] = ['key' => $envKey, 'value' => $request[$input]];
        }

        $this->updateEnvKeys($keys);

        \Artisan::call("config:clear");

        return redirect()->back()->withSuccess(__('Successfully updated'));
    }

    public function testEmail()
    {
        return inertia('Admin/Settings/TestEmail', [
            'data' => [
                'title'               => __('Test Email Configuration'),
                'previous_page_title' => __('Back') . ' ' . __('to') . ' ' . __('Email Configuration'),
                'urls'                => [
                    'submit_form'   => route('admin.settings.email.test.update'),
                    'previous_page' => route('admin.settings.email'),
                ],
            ],

        ]);
    }

    public function sendTestEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        try {
            Mail::to($request->email)->send(new TestMail());
            return redirect()->back()->withSuccess(__('Email sent'));
        } catch (TransportException $e) {
        } catch (\Exception $e) {
        } catch (\Throwable $e) {
        }
        return redirect()->back()->withFail(__('Could not send the email. Please check your email settings'));
    }

    public function paymentGateways(Request $request, PaymentGatewaySettingsService $service)
    {
        $config = config('paymentgateways');

        if ($service->isValidGateway($request->gateway, $config['gateways'])) {
            abort(404);
        }

        return inertia('Admin/Settings/PaymentGateways/Online', [
            'data' => $service->recordsForSettingsPage($request->gateway, $config),
        ]);
    }

    public function google_analytics()
    {
        $data['records'] = $this->get_records([
            'google_analytics_tracking_code',
        ]);

        return view('setup.google_analytics', compact('data'));
    }

    public function update_google_analytics(Request $request)
    {
        $this->saveRecords([
            'google_analytics_tracking_code' => $request->input('google_analytics_tracking_code'),
        ]);

        return redirect()->back()->withSuccess(__('Successfully updated'));
    }

    public function cache()
    {
        return inertia('Admin/Settings/Cache', [
            'data' => [
                'title' => __('Clear Cache'),
                'urls'  => [
                    'submit_form' => route('admin.settings.clear.cache'),
                ],

            ],
        ]);
    }

    public function clearCache(Request $request)
    {
        Cache::flush();
        return redirect()->back()->withSuccess(__('Cache cleared'));
    }

    public function websiteUi()
    {
        return inertia('Admin/Settings/WebsiteUi', [
            'records' => $this->getRecords(array_keys(Setting::websiteUiFields())),
            'data'    => [
                'title'     => __('Website UI'),
                'dropdowns' => [
                    'image_positions' => HomePageSection::getImagePositions(),
                ],
            ],
        ]);
    }

    public function updateWebsiteUi(Request $request)
    {
        $data = $request->validate(Setting::websiteUiFields());

        $this->saveRecords($data);

        return redirect()->back()->withSuccess(__('Successfully updated'));
    }

}
