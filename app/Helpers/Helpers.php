<?php

use App\Enums\OperationType;
use App\Models\ProjectManagement\TaskStatus;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

include_once 'form_helper.php';
include_once 'currency_helper.php';
include_once 'date_helper.php';
include_once 'language_helper.php';
include_once 'user_link_generator.php';

// For cached settings only
function settings($key)
{
    $record = null;

    $setting = \Illuminate\Support\Facades\Cache::rememberForever('settings', function () {
        return \App\Models\Setting::all();
    });

    if ($setting && $setting->count() > 0) {
        $record = $setting->where('option_key', $key);
    }

    if (!empty($record) && !empty(optional($record->first())->option_value)) {
        return $record->first()->option_value;
    } else {
        return \App\Models\Setting::get_setting($key);
    }
}

function business_operation_type()
{
    return settings('business_operation_type');
}

function is_bidding_enable()
{
    if (in_array(settings('business_operation_type'), [OperationType::BIDDING, OperationType::COMBINED])) {
        return true;
    }
}

function is_ordering_enable()
{
    if (in_array(settings('business_operation_type'), [OperationType::DIRECT_ORDER, OperationType::COMBINED])) {
        return true;
    }
}

function is_quality_control_enable()
{
    return (settings('disable_quality_control') == 'yes') ? false : true;
}

function is_self_assigning_tasks_enable()
{
    return (settings('enable_self_assigning_tasks')) ? true : false;
}

function get_asset_from_storage($file)
{
    return URL::to($file);
}

function get_company_logo()
{
    if ($company_logo = settings('company_logo')) {

        return get_asset_from_storage($company_logo);
        //return asset(Storage::url($company_logo));
    }
}

function get_company_address()
{
    return settings('company_address');
}

function get_website_logo()
{
    if ($website_logo = settings('website_logo')) {

        return asset($website_logo);
    }
}

function get_website_favicon()
{
    if ($website_favicon = settings('website_favicon')) {

        return asset($website_favicon);
    }
}

function get_payment_gateway_image()
{
    if ($website_accept_payment_image = settings('website_accept_payment_image')) {

        return asset($website_accept_payment_image);
    }
}
 

function pr($data)
{
    echo "<pre>";
    print_r($data);
    die();
}

function _debug($e)
{
    echo $e->getMessage() . " <br> " . $e->getLine() . "<br>" . $e->getFile();
    die();
}

function get_company_name()
{
    return Purifier::clean(settings('company_name'));
}

function get_company_phone()
{
    return Purifier::clean(settings('company_phone'));
}

function get_company_email()
{
    return settings('company_email');
}

function get_tinymce_key()
{
    return settings('tinymce_key');
}

function get_software_version()
{
    return '2.0';
}

function format_money($amount)
{
    return format_currency($amount, true);
}

function load_route($route_name)
{
    require base_path() . '/routes/splitted/' . $route_name . '.php';
}

function company_notification_email()
{
    return settings('company_notification_email');
}

function send_notification_to_company($notifiable)
{
    if ($email = company_notification_email()) {
        \Illuminate\Support\Facades\Notification::route('mail', $email)->notify($notifiable);
    }
}

function send_notification_to_admins($notifiable)
{
    \Illuminate\Support\Facades\Notification::send(\App\Models\User::admins()->get(), $notifiable);
}

function send_notification_to_task_followers($notifiable, $task, array $except_users = [], $push_users = null)
{
    $followers = $task->followers();
    if (is_array($except_users) && count($except_users) > 0) {
        $followers->whereNotIn('user_id', $except_users);
    }
    $followers = $followers->get();

    if (is_array($push_users) && count($push_users) > 0) {
        foreach ($push_users as $push_user) {
            $followers = $followers->push($push_user);
        }
    }

    \Illuminate\Support\Facades\Notification::send($followers, $notifiable);
}

function add_task_follower($task, $follower, $unFollower)
{
    if ($follower) {
        if (!$task->followers->contains($follower)) {
            // If the follower is not already added
            $task->followers()->attach($follower);
        }
    }

    if ($unFollower && ($follower != $unFollower)) {
        // If the un follower is not empty and not as same as the follower
        $task->followers()->detach($unFollower);
    }
}

function user_photo($photo)
{
    return ($photo) ? asset(Storage::url($photo)) : asset('images/user-placeholder.jpg');
}

function push_notification($user_id)
{
    $notification = \App\Models\PushNotification::updateOrCreate([
        'user_id' => $user_id,
    ]);
    $notification->number++;
    $notification->save();
}

function is_filter_contact_info_from_message_enabled()
{
    return (settings('filter_contact_info_from_message')) ? true : false;
}

function get_open_ai_api()
{
    return settings('open_ai_api_key');
}

function get_path_for_build_files()
{
    $build_directory = 'build';
    
    $sub_directory = '/'. get_sub_folder_if_exists();
    
    return ($sub_directory) ? $sub_directory . '/' . $build_directory : $build_directory;

}

function get_sub_folder_if_exists()
{
    $parsed_url = parse_url(env('APP_URL'));

    if (isset($parsed_url['path'])) {
        return trim(str_replace('/', '', $parsed_url['path']));
    }
    return null;
}

function currencyConfig()
{
    $format            = (settings('currency_position') == 'after_amount') ? '%v %s' : '%s%v';
    $thousandSeparator = (settings('thousand_separator') == 'empty_space') ? ' ' : settings('thousand_separator');

    return json_encode([
        'currency' => [
            'symbol'  => settings('currency_symbol'),
            'format'  => $format,
            'decimal' => settings('decimal_symbol'), // decimal point separator
            'thousand' => $thousandSeparator, // thousands separator
            'precision' => settings('currency_precision'),
        ],
        'number'   => [
            'precision' => settings('currency_precision'),
            'thousand'  => $thousandSeparator,
            'decimal'   => settings('decimal_symbol'),
        ],
    ]);
}

function is_active_menu($url)
{
    return (request()->fullUrl() == $url) ? 'active' : '';
}

function display_html_content($content)
{
    return nl2br(stripcslashes($content));
}

function get_website_css($appearance, array $fields)
{
    $css = '';

    foreach ($fields as $field) {
        switch ($field) {
            case 'bg_color':
                $style_name = 'background';
                break;
            case 'text_color':
                $style_name = 'color';
                break;
            case 'header_minimum_height':
                $style_name = 'min-height';
                break;
            default:
                $style_name = null;
                break;
        }

        if ($style_name && isset($appearance[$field]) && $appearance[$field]) {
            $css .= $style_name . ":" . $appearance[$field] . ';';
        }
    }

    return $css;
}

function get_website_text_alignment_class($appearance, $field)
{
    $field_value = isset($appearance[$field]) ? $appearance[$field] : null;

    switch ($field_value) {
        case 'left':
            $style_name = 'text-md-start';
            break;
        case 'right':
            $style_name = 'text-md-end';
            break;
        case 'center':
            $style_name = 'text-md-center';
            break;
        default:
            $style_name = null;
            break;
    }
    return $style_name;

}

function website_star_rating($number)
{
    $stars = 5;

    for ($i = 1; $i <= $stars; $i++) {
        $fill = ($i <= $number) ? 'star-filled' : 'star-unfilled';
        echo '<i class="fas fa-1x fa-star ' . $fill . '"></i>';
    }
}

function showErrorClass($errors, $key)
{
    if ($errors->has($key)) {
        echo 'is-invalid';
    }
}

function showError($errors, $key)
{
    if ($errors->has($key)) {
        echo $errors->first($key);
    }
}

function recaptchaHtml($errors)
{
    ?>
    <input type="hidden" name="recaptcha" id="recaptcha">
    <div class="invalid-feedback d-block"><?php echo showError($errors, 'recaptcha'); ?></div>
<?php
}
function recaptchaJavascript()
{
    ?>
    <script data-callback="enableBtn" src="https://www.google.com/recaptcha/api.js?render=<?php echo settings('recaptcha_site_key'); ?>"></script>
    <script>
        grecaptcha.ready(function() {
            grecaptcha.execute('<?php echo settings('recaptcha_site_key'); ?>', {
                action: 'contact'
            }).then(function(token) {
                if (token) {
                    document.getElementById('recaptcha').value = token;
                    document.getElementById("submitButton").disabled = false;
                }
            });
        });
    </script>
<?php

}

function is_boolean_true($value)
{
    return filter_var($value, FILTER_VALIDATE_BOOLEAN);
}

function disable_author_application()
{
    return settings('disable_author_application');
}

function hide_author_application_link()
{
    return (disable_author_application() || settings('hide_author_application_link_from_website'));
}

function disable_order_page_for_unauthenticated_user()
{
    return (settings('disable_order_page_for_unauthenticated_user'));
}

function is_social_login_enable()
{
    return settings('is_google_enable');
}

function get_base_url($query_param = null)
{
    return URL::to($query_param);

    if (is_single_language()) {
        return URL::to($query_param);
    }

    if ($query_param) {
        $query_param = '/' . $query_param;
    }
    return URL::to(App::currentLocale() . $query_param);

}

function is_display_download_work_allowed($task_status_id)
{
    if (in_array($task_status_id, [TaskStatus::SUBMITTED_FOR_APPROVAL, TaskStatus::COMPLETE])) {

        return true;
    }

    return false;
}

// function get_app_root_path_name()
// {
//     return 'app';
// }

// function get_app_root_path()
// {
//     if (!is_website_disable()) {
//         return get_app_root_path_name();
//     }
// }
