<?php

namespace App\Enums;

abstract class PriceType
{
    const FIXED                = 'fixed';
    const PERCENTAGE           = 'percentage';
    // const PER_UNIT             = 'per_unit';
    const PER_ENTERED_QUANTITY = 'per_entered_quantity';

    public static function getNames()
    {
        return [
            self::FIXED                => __('Fixed'),
            self::PERCENTAGE           => __('Percentage'),
            // self::PER_UNIT             => __('Per Unit'),
            self::PER_ENTERED_QUANTITY => __('Per entered quantity'),
        ];
    }

    public static function asDropdown()
    {
        return [
            ['id' => self::FIXED, 'name' => __('Fixed'), 'description' => __('The defined price will be added with order total')],
            ['id' => self::PERCENTAGE, 'name' => __('Percentage'), 'description' => __('The price will be calculated using the defined rate of order total')],
            // ['id' => self::PER_UNIT, 'name' => __('Per Unit'), 'description' => __('The price will be calculated using the number of words or pages entered')],
            ['id' => self::PER_ENTERED_QUANTITY, 'name' => __('Per Entered Quantity'), 'description' => __('The price will be calculated using the defined amount per entered quantity')],

        ];
    }
}
