<?php

namespace App\Enums;

abstract class UnitType
{
    const WORD = 'word';
    const PAGE = 'page';

    static function get()
    {
        return [
            self::WORD => __('Words'),
            self::PAGE => __('Pages'),
        ];
    }

    static function dropdown()
    {
        $list = self::get();
        return [
            [
                'id' => self::WORD,
                'name' => $list[self::WORD],
            ],

            [
                'id' => self::PAGE,
                'name' => $list[self::PAGE],
            ],

        ];
    }
}
