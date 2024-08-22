<?php

define('FORMAT_CURRENCY_METHOD_ONE', 1);
define('FORMAT_CURRENCY_METHOD_TWO', 2);
define('FORMAT_CURRENCY_METHOD_THREE', 3);

function applyCurrencySymbol($amount, $symbol, $position)
{
    return (($position == 'after_amount')) ? ($amount . ' ' . $symbol) : ($symbol . '' . $amount);
}

function format_currency($input, $with_currency = FALSE, $cur_symbol = NULL)
{
    //return '$'. number_format($input, 2, '.', ',');

    $method                     = settings('digit_grouping_method');
    $round_precision            = (settings('currency_precision')) ? settings('currency_precision') : 2;
    $decimal_symbol             = settings('decimal_symbol');
    $digit_grouping_symbol      = (settings('thousand_separator') == 'empty_space') ? ' ' : settings('thousand_separator');
    $currency_symbol            = settings('currency_symbol');
    $currency_position          = (settings('currency_position')) ? settings('currency_position') : 'before_amount';
    
    // $method                 = 1;
    // $round_precision            = 2 ;
    // $decimal_symbol             =  '.' ;
    // $digit_grouping_symbol      =  ',' ;
    // $currency_symbol            = '$';


    if ($method == FORMAT_CURRENCY_METHOD_ONE) {
        $num_of_digits_to_separate_from_last_part   = 3;
        $num_of_digits_for_grouping                 = 3;
    } elseif ($method == FORMAT_CURRENCY_METHOD_TWO) {
        $num_of_digits_to_separate_from_last_part   = 3;
        $num_of_digits_for_grouping                 = 2;
    } elseif ($method == FORMAT_CURRENCY_METHOD_THREE) {
        $num_of_digits_to_separate_from_last_part   = 4;
        $num_of_digits_for_grouping                 = 4;
    }

    $val = format_currency_helper($input, $round_precision, $decimal_symbol, $digit_grouping_symbol, $num_of_digits_to_separate_from_last_part, $num_of_digits_for_grouping);
    //$val = round($val, 2);
    $symbol = ($cur_symbol) ? $cur_symbol : $currency_symbol;

    if ($val) {
        if ($with_currency) {

            if ($val < 0) {
                return '- ' . applyCurrencySymbol(str_replace('-', '', $val), $symbol, $currency_position);
            } else {

                return applyCurrencySymbol($val, $symbol, $currency_position);
            }
        } else {
            return $val;
        }
    } elseif ($val == 0) {
        return ($with_currency) ? applyCurrencySymbol(0, $symbol, $currency_position) : 0;
    } else {
        return null;
    }
}



/*
 * Currency Formatting Types
    Style A: 10,000,000,000 // Most currencies
    Style B: 10,00,00,00,000 // South East Asian
    Style C: 100,0000,0000 // Japan, China
 */

// Covers Most currencies in the world
function format_currency_helper($input, $round_precision, $decimal_symbol, $digit_grouping_symbol, $num_of_digits_to_separate_from_last_part, $num_of_digits_for_grouping)
{
    $is_negative = false;
    if ($input < 0) {
        $is_negative = true;
        $input = abs($input);
    }
    //CUSTOM FUNCTION TO GENERATE ##,##,###.##
    $decimal = null;
    if (strpos($input, ".") !== false) {
        list($input, $decimal) = explode('.', $input);
    }
    

    //get the last 3 digits
    $num = substr($input, -$num_of_digits_to_separate_from_last_part); 

    //omit the last 3 digits already stored in $num
    $input = substr($input, 0, -$num_of_digits_to_separate_from_last_part); 

    //loop the process - further get digits 2 by 2
    while (strlen($input) > 0) 
    {
        $num = substr($input, -$num_of_digits_for_grouping) . $digit_grouping_symbol . $num;
        $input = substr($input, 0, -$num_of_digits_for_grouping);
    }
    if($decimal && !empty($decimal))
    {
        $a = $num . $decimal_symbol . $decimal;
    }
    else
    {
        $a = $num;
    }    

    return ($is_negative == true) ? "-" . $a : $a;
}
