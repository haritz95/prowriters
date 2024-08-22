<?php

namespace App\Enums;

abstract class CouponType
{
    const Percentage        = 'percentage';
    const Fixed             = 'fixed';


    static function asDropdown()
    {
        return [
            ['id' => self::Percentage, 'name' => __('Percentage')],
            ['id' => self::Fixed, 'name' => __('Fixed')],
        ];
    }
}
