<?php

namespace App\Traits;

trait WhereDateBetweenTrait
{

    /**
     * Scope a query to only include the last n days records
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereDateBetween($query, $fieldName, $fromDate, $todate)
    {
        return $query->whereDate($fieldName, '>=', $fromDate)->whereDate($fieldName, '<=', $todate);
    }

}
