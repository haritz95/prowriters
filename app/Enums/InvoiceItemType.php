<?php

namespace App\Enums;

abstract class InvoiceItemType
{
    const NEW_TASK      = 'new_task';
    const EXISTING_TASK = 'existing_task';

    // public static function asDropdown()
    // {
    //     return [
    //         ['id' => self::Percentage, 'name' => __('Percentage')],
    //         ['id' => self::Fixed, 'name' => __('Fixed')],
    //     ];
    // }
}
