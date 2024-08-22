<?php

namespace App\Models\ProjectManagement;

use Illuminate\Database\Eloquent\Model;

class TaskStatus extends Model
{

    public $timestamps = false;

    const NEW = 1;
    const IN_PROGRESS = 2;
    const SUBMITTED_FOR_APPROVAL = 3;
    const REQUESTED_FOR_REVISION = 4;
    const COMPLETE = 5;
    const ON_HOLD = 6;
    const CANCELED = 7;
    const SUBMITTED_FOR_QA = 8;
    const QA_REJECTED = 9;

    // const REQUESTED_FOR_BID = 10;

    public function getNameAttribute($value)
    {
        if (app()->getLocale() != 'en') {
            return __($value);
        }
        return $value;
    }

    static function getList()
    {
        return [
            ['name' => __('New'), 'color' => '#007bff', 'bg_color' => '#f8f9fa'],
            ['name' => __('In progress'), 'color' => '#17a2b8', 'bg_color' => '#f8f9fa'],
            ['name' => __('Submitted for approval'), 'color' => '#17a2b8', 'bg_color' => '#f8f9fa'],
            ['name' => __('Requested for revision'), 'color' => '#ffc107', 'bg_color' => '#f8f9fa'],
            ['name' => __('Completed'), 'color' => '#28a745', 'bg_color' => '#f8f9fa'],
            ['name' => __('On hold'), 'color' => '#6c757d', 'bg_color' => '#f8f9fa'],
            ['name' => __('Canceled'), 'color' => '#343a40', 'bg_color' => '#f8f9fa'],
            ['name' => __('Submitted for QA'), 'color' => '#007bff', 'bg_color' => '#f8f9fa'],
            ['name' => __('QA Rejected'), 'color' => '#dc3545', 'bg_color' => '#f8f9fa'],
            
        ];
    }
}
