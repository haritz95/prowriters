<?php

namespace App\Enums;

abstract class UserType
{
    const ADMIN    = 1;
    const CUSTOMER = 2;
    const AUTHOR   = 3;

    public static function get()
    {
        return [
            'admin'    => self::ADMIN,
            'customer' => self::CUSTOMER,
            'author'   => self::AUTHOR,
        ];
    }
}
