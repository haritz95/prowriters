<?php

namespace App\Enums;

abstract class BidRequestStatusType
{
    public const OPEN      = 1;
    public const HIRED     = 2;
    public const ON_HOLD   = 3;
    public const CANCELLED = 4;

    public static function frontend()
    {
        return [
            'open'      => self::OPEN,
            'hired'     => self::HIRED,
            'on_hold'   => self::ON_HOLD,
            'cancelled' => self::CANCELLED,
        ];
    }

    public static function getList()
    {
        return [
            [
                'id'       => self::OPEN,
                'name'     => __('Open'),
                'bg_color' => '#3498db',
                'color'    => '#ffffff',
            ],
            [
                'id'       => self::HIRED,
                'name'     => __('Hired'),
                'bg_color' => '#2ecc71',
                'color'    => '#fff',
            ],
            [
                'id'       => self::ON_HOLD,
                'name'     => __('On Hold'),
                'bg_color' => '#f39c12',
                'color'    => '#fff',
            ],
            [
                'id'       => self::CANCELLED,
                'name'     => __('Cancelled'),
                'bg_color' => '#9b59b6',
                'color'    => '#fff',
            ],

        ];
    }

}
