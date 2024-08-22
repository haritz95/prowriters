<?php

namespace App\Enums;

abstract class OperationType
{
    const BIDDING      = 'bidding';
    const DIRECT_ORDER = 'direct_order';
    const COMBINED     = 'combined';

    public static function frontEndConstant()
    {
        return [
            'bidding'      => self::BIDDING,
            'direct_order' => self::DIRECT_ORDER,
            'combined'     => self::COMBINED,
        ];
    }

    public static function get()
    {
        return [
            [
                'id'          => self::BIDDING,
                'name'        => __('Bidding'),
                'description' => __('Customers post jobs, authors bid on them and the client finally choose the author'),
            ],
            [
                'id'          => self::DIRECT_ORDER,
                'name'        => __('Ordering'),
                'description' => __('Admins sets price, Customers place orders, Admins assign to authors, Authors deliver them'),
            ],
            [
                'id'          => self::COMBINED,
                'name'        => __('Both Bidding and Ordering'),
                'description' => 'All features in Bidding and Ordering',
            ],
        ];
    }
}
