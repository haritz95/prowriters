<?php

namespace App\Enums;

abstract class ServiceType
{
    const CONTENT_WRITING  = 1;
    const ACADEMIC_WRITING = 2;
    const FIXED_PRICE      = 3;

    public static function get()
    {
        return [
            self::ACADEMIC_WRITING => __('Academic Writing'),
            self::CONTENT_WRITING  => __('Content Writing'),
            self::FIXED_PRICE      => __('Fixed Price'),
        ];
    }

    public static function getForFrontEnd()
    {
        return [
            'academic_writing' => self::ACADEMIC_WRITING,
            'content_writing'  => self::CONTENT_WRITING,
            'fixed_price'      => self::FIXED_PRICE,
        ];
    }

    public static function dropdownForFrontEnd()
    {
        return [
            ['id' => self::ACADEMIC_WRITING, 'name' => __('Academic Writing')],
            ['id' => self::CONTENT_WRITING, 'name' => __('Content Writing')],
            ['id' => self::FIXED_PRICE, 'name' => __('Fixed Price')],

        ];
    }

    public static function icons()
    {
        return [
            self::ACADEMIC_WRITING => asset('images/service-types/writing.png'),
            self::CONTENT_WRITING  => asset('images/service-types/problem-solving.png'),
            self::FIXED_PRICE      => asset('images/service-types/resume.png'),
        ];
    }

}
