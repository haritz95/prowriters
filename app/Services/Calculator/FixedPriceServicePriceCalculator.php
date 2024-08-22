<?php

namespace App\Services\Calculator;

use App\Models\Business\Assignment;

class FixedPriceServicePriceCalculator
{

    private $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function get()
    {
        $assignment = Assignment::with('urgency')->find($this->request->assignment_id);
        $urgency    = $assignment->urgency;

        return [
            'amount'               => $assignment->price,
            'quantity'             => 1,
            'author_level_id'      => $assignment->author_level_id,
            'revisions_allowed'    => $assignment->number_of_revisions_allowed,
            'dead_line'            => get_urgency_date($urgency->type, $urgency->value, 'Y-m-d H:i:s'),
            'dead_line_for_author' => get_urgency_date($urgency->type_for_writer, $urgency->value_for_writer, 'Y-m-d H:i:s'),

        ];

    }

}
