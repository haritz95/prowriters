<?php

function format_date_from_client_side($date)
{
    return \Carbon\Carbon::parse($date)->setTimezone('UTC')->format('Y-m-d H:i:s');
}

function format_date_range_from_client_side($date_range, $format = "Y-m-d")
{
    $data['from'] = null;
    $data['to']   = null;
    try {
        list($from, $to) = $date_range;
        $data['from']    = \Carbon\Carbon::parse($from)->format($format);
        $data['to']      = \Carbon\Carbon::parse($to)->format($format);

    } catch (\Exception$e) {}
    return $data;

}

function get_urgency_date($type, $value, $format = 'D, M j, Y')
{
    $now = \Carbon\Carbon::now();

    $now = ($type == 'hours') ? $now->addHours($value) : $now->addDays($value);

    return $now->format($format);
}

// function sql2date($date)
// {
//     return date("d-M-Y", strtotime($date));
// }

function get_list_of_months($month = null)
{
    return [
        'January'   => __('January'),
        'February'  => __('February'),
        'March'     => __('March'),
        'April'     => __('April'),
        'May'       => __('May'),
        'June'      => __('June'),
        'July'      => __('July'),
        'August'    => __('August'),
        'September' => __('September'),
        'October'   => __('October'),
        'November'  => __('November'),
        'December'  => __('December'),
        'Jan'       => __('Jan'),
        'Feb'       => __('Feb'),
        'Mar'       => __('March'),
        'Apr'       => __('Apr'),
        'May'       => __('May'),
        'Jun'       => __('Jun'),
        'Jul'       => __('Jul'),
        'Aug'       => __('Aug'),
        'Sep'       => __('Sep'),
        'Oct'       => __('Oct'),
        'Nov'       => __('Nov'),
        'Dec'       => __('Dec'),
    ];
}

function translate_month_from_english($monthInEnglish)
{
    if (app()->getLocale() != 'en') {
        $months = get_list_of_months();
        if (isset($months[$monthInEnglish])) {
            return $months[$monthInEnglish];
        }
    }
    return $monthInEnglish;
}
function translate_month_name($date, $format)
{
    $newDate = $date->format($format);

    if (app()->getLocale() != 'en') {
        $months = get_list_of_months();

        if (isset($months[$date->format('F')])) {
            $translatedText = $months[$date->format('F')];
            $newDate        = str_replace($date->format('F'), $translatedText, $newDate);
        }
        if (isset($months[$date->format('M')])) {

            $translatedText = $months[$date->format('M')];
            $newDate        = str_replace($date->format('M'), $translatedText, $newDate);
        }
    }
    return $newDate;
}

function localize_date($date)
{
    return convert_to_local_time($date, 'd-M-Y');
}

function convert_to_local_time($value, $format = 'd M Y H:i:s')
{
    $timezone = isset(auth()->user()->timezone) ? auth()->user()->timezone : 'utc';

    if ($value instanceof \Illuminate\Support\Carbon) {
        $date = $value->setTimezone($timezone);
    } else {
        $date = (new Carbon\Carbon($value))->setTimezone($timezone);
    }

    if (app()->getLocale() != 'en') {
        return translate_month_name($date, $format);
    } else {
        return $date->format($format);
    }
}
