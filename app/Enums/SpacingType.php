<?php

namespace App\Enums;

abstract class SpacingType
{
    const DOUBLE                    = 'double';
    const ONE_POINT_FIVE            = '1_5';
    const SINGLE                    = 'single';

    static function listOfNumberOfWords()
    {
        return [
            self::DOUBLE                    => 275,
            self::ONE_POINT_FIVE            => 400,
            self::SINGLE                    => 550,
        ];
    }

    static function get()
    {
        return [
            self::DOUBLE                    => __("Double"),
            self::ONE_POINT_FIVE            => __("1.5 Lines"),
            self::SINGLE                    => __("Single"),
        ];
    }

    static function dropdown()
    {
        $number_of_words = self::listOfNumberOfWords();
        $list = self::get();

        return [
            [
                'id' => self::DOUBLE,
                'name' => $list[self::DOUBLE],
                'number_of_words' => $number_of_words[self::DOUBLE],
            ],

            [
                'id' => self::ONE_POINT_FIVE,
                'name' => $list[self::ONE_POINT_FIVE],
                'number_of_words' => $number_of_words[self::ONE_POINT_FIVE],
            ],
            [
                'id' => self::SINGLE,
                'name' => $list[self::SINGLE],
                'number_of_words' => $number_of_words[self::SINGLE],
            ],

        ];
    }
}
