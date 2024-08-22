<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{

    protected $fillable = [
        'option_key',
        'option_value',
    ];

    public $timestamps = false;

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            Cache::flush();
        });

        self::updating(function ($model) {
            Cache::flush();
        });
    }

    public static function currency_dropdown()
    {
        $data['decimal_symbols'] = array(
            ['id' => '.', 'name' => '. (Dot)'],
            ['id' => ',', 'name' => ', (Comma)'],
        );

        $data['thousand_separators'] = [
            ['id' => '.', 'name' => '. (Dot)'],
            ['id' => ',', 'name' => ', (Comma)'],
            ['id' => 'empty_space', 'name' => __('Empty Space')],
        ];

        $data['digit_grouping_methods'] = [
            ['id' => FORMAT_CURRENCY_METHOD_ONE, 'name' => "10,000,000,000"],
            ['id' => FORMAT_CURRENCY_METHOD_TWO, 'name' => "10,00,00,00,000"],
            ['id' => FORMAT_CURRENCY_METHOD_THREE, 'name' => "100,0000,0000"],
        ];

        $data['currency_positions'] = array(
            ['id' => 'before_amount', 'name' => __('Before Amount')],
            ['id' => 'after_amount', 'name' => __('After Amount')],
        );

        $data['currency_precisions'] = [
            ['id' => 2, 'name' => 'Two'],
            ['id' => 3, 'name' => 'Three'],
            ['id' => 4, 'name' => 'Four'],
            ['id' => 5, 'name' => 'Five'],
            ['id' => 6, 'name' => 'Six'],
            ['id' => 7, 'name' => 'Seven'],
        ];

        return $data;
    }

    public static function isJSON($string)
    {
        return is_string($string) && is_array(json_decode($string, true)) ? true : false;
    }

    public static function get_setting($key)
    {
        $records = self::where('option_key', $key)->get();

        if ($records->count() > 0) {
            $string = $records->first()->option_value;

            if (is_string($string) && is_array(json_decode($string, true))) {
                return json_decode($string);
            } else {
                return $string;
            }
        }

        return false;
    }

    public static function save_settings($data, $auto_load_disabled = null)
    {

        foreach ($data as $key => $value) {
            $obj = self::updateOrCreate([
                'option_key' => $key,
            ]);
            $obj->option_value       = trim($value);
            $obj->auto_load_disabled = $auto_load_disabled;
            $obj->save();
        }
    }

    public static function get(array $optionKeys)
    {
        $settings = self::query();
        $keys     = new \stdClass();

        foreach ($optionKeys as $index => $key) {
            if ($index == 0) {
                $settings->orWhere('option_key', $key);
            } else {
                $settings->orWhere('option_key', $key);
            }
            $keys->{$key} = null;
        }

        foreach ($settings->get() as $settings) {
            $keys->{$settings['option_key']} = $settings['option_value'];
        }
        return $keys;
    }

    public static function socialLoginCallbackUrls($provider = null)
    {
        if ((app()->environment() == 'local')) {
            $data['google'] = route('handle_google_auth_callback');
            // $data['facebook'] = 'http://localhost:8000/auth/facebook/callback';
            // $data['twitter']  = 'http://localhost:8000/auth/twitter/callback';
            // $data['linkedin'] = 'http://localhost:8000/auth/linkedin/callback';
        } else {
            $data['google'] = route('handle_google_auth_callback');
            // $data['facebook'] = route('handle_facebook_auth_callback');
            // $data['twitter']  = route('handle_twitter_auth_callback');
            // $data['linkedin'] = route('handle_linkedin_auth_callback');
        }

        return ($provider && isset($data[$provider])) ? $data[$provider] : $data;
    }

    public static function emailFields()
    {
        return [
            'mail_mailer'       => 'MAIL_MAILER',
            'mail_host'         => 'MAIL_HOST',
            'mail_port'         => 'MAIL_PORT',
            'mail_username'     => 'MAIL_USERNAME',
            'mail_password'     => 'MAIL_PASSWORD',
            'mail_encryption'   => 'MAIL_ENCRYPTION',
            'mail_from_address' => 'MAIL_FROM_ADDRESS',
            'mailgun_domain'    => 'MAILGUN_DOMAIN',
            'mailgun_secret'    => 'MAILGUN_SECRET',
            'queue_connection'  => 'QUEUE_CONNECTION',

        ];
    }

    public static function websiteUiFields()
    {
        return [
            'website_top_nav_bg_color'                  => 'nullable',
            'website_top_nav_link_color'                => 'nullable',
            'website_top_nav_link_hover_color'          => 'nullable',
            'website_footer_bg_color'                   => 'nullable',
            'website_footer_text_color'                 => 'nullable',
            'website_footer_link_color'                 => 'nullable',
            'website_footer_link_hover_color'           => 'nullable',
            'website_order_now_button_bg_color'         => 'nullable',
            'website_order_now_button_text_color'       => 'nullable',
            'website_order_now_button_hover_bg_color'   => 'nullable',
            'website_order_now_button_hover_text_color' => 'nullable',
            'website_logo'                              => 'nullable',
            'website_favicon'                           => 'nullable',
            'website_accept_payment_image'              => 'nullable',
            'website_hero_image'                        => 'nullable',
            'website_hero_image_position'               => 'nullable',
            'website_hero_bg_color'                     => 'nullable',
            'website_hero_text_color'                   => 'nullable',
            'website_link_color'                        => 'nullable',
            'website_link_hover_color'                  => 'nullable',

        ];
    }

    public static function generalFields()
    {
        return [
            'company_name'                              => 'required',
            'company_phone'                             => 'required',
            'company_email'                             => 'required|email',
            'company_address'                           => 'required',
            'company_notification_email'                => 'required|email',
            'hide_website'                              => 'nullable|boolean',
            'hide_blog'                                 => 'nullable|boolean',
            'hide_author_application_link_from_website' => 'nullable|boolean',
        ];
    }

    public static function serviceFields()
    {
        return [
            // 'number_of_revision_allowed'   => 'required',
            // 'commission_rate_from_bid'     => 'required_if:business_operation_type,' . OperationType::BIDDING . ',' . OperationType::COMBINED . '|max:100|min:0|numeric',
            'business_operation_type'                  => 'required',
            'payout_amount_threshold'                  => 'required|numeric|min:0',
            'dead_line_extension_by_type'              => 'required',
            'dead_line_extension_by_value'             => 'required|numeric|min:0',
            'disable_quality_control'                  => 'required',
            'enable_sales_tax'                         => 'nullable',
            'sales_tax_rate'                           => 'required_if:enable_sales_tax,1',
            'enable_self_assigning_tasks'              => 'nullable|boolean',
            'default_receipt_id_for_incoming_messages' => 'required',
        ];
    }

    public static function googleTagManagerFields()
    {
        return [
            'google_tag_id' => 'nullable',
        ];
    }

    public static function appSettingsFields()
    {
        return [
            'company_logo'                                => 'nullable',
            'tinymce_key'                                 => 'nullable',
            'disable_author_application'                  => 'nullable|boolean',
            'disable_order_page_for_unauthenticated_user' => 'nullable|boolean',
            'filter_contact_info_from_message'            => 'nullable|boolean',
            'application_top_nav_bg_color'                => 'nullable',
            'application_link_color'                      => 'nullable',
            'application_link_hover_color'                => 'nullable',
            'open_ai_api_key'                             => 'nullable',
        ];
    }

    public static function switchLanguage(Request $request, $language)
    {
        if (in_array($language, allowed_languages())) {
            //URL::defaults(['lc' => get_locale_from_url()]);
            App::setlocale($language);
            Carbon::setLocale($language);
            $request->session()->put('locale', $language);
            //$request->route()->forgetParameter('lc');
            return true;
        }
    }
}
