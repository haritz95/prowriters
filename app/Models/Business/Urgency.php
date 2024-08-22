<?php

namespace App\Models\Business;

use Illuminate\Database\Eloquent\Model;

class Urgency extends Model
{

    const TYPE_HOURS = 'hours';
    const TYPE_DAYS  = 'days';

    protected $fillable = [
        'name',
        'type',
        'value',
        'type_for_author',
        'value_for_author',
        'percentage',
    ];

    public static function types()
    {
        return [
            ['id' => self::TYPE_HOURS, 'name' => __('Hours')],
            ['id' => self::TYPE_DAYS, 'name' => __('Days')],
        ];
    }
}
