<?php

namespace App\Enums;

abstract class WorkType
{
    const WRITING      = 'writing';
    const EDITING      = 'editing';
    const PROOFREADING = 'proofreading';

    public static function get()
    {
        return [
            self::WRITING      => __('Writing'),
            self::EDITING      => __('Editing'),
            self::PROOFREADING => __('Proofreading'),
        ];
    }

    public static function dropdown()
    {
        $list = self::get();
        return [
            [
                'id'   => self::WRITING,
                'name' => $list[self::WRITING],
            ],

            [
                'id'   => self::EDITING,
                'name' => $list[self::EDITING],
            ],
            [
                'id'   => self::PROOFREADING,
                'name' => $list[self::PROOFREADING],
            ],

        ];
    }

    public static function dropdownByServiceSettings($disable_writing = null, $disable_editing = null, $disable_proofreading = null)
    {
        $list = self::get();
        $data = [];

        if (!$disable_writing) {
            array_push($data, [
                'id'   => self::WRITING,
                'name' => $list[self::WRITING],
            ]);
        }
        if (!$disable_editing) {
            array_push($data, [
                'id'   => self::EDITING,
                'name' => $list[self::EDITING],
            ]);
        }
        if (!$disable_proofreading) {
            array_push($data, [
                'id'   => self::PROOFREADING,
                'name' => $list[self::PROOFREADING],
            ]);
        }
        return $data;
    }

}
