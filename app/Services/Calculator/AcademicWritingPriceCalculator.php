<?php

namespace App\Services\Calculator;

use App\Models\Business\AcademicLevel;
use App\Models\Business\Assignment;
use App\Models\Business\AuthorLevel;
use App\Models\Business\Subject;
use App\Models\Business\Unit;

class AcademicWritingPriceCalculator
{

    private $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function get()
    {

        $request = $this->request;

        $assignment     = Assignment::find($request->assignment_id);
        $unit           = Unit::with('urgency')->find($request->unit_id);
        $subject        = Subject::find($request->subject_id);
        $academic_level = AcademicLevel::find($request->academic_level_id);
        $author_level   = AuthorLevel::find($request->author_level_id);

        $subject_price        = $this->getPriceFromPercentage($unit->price, $subject->percentage);
        $academic_level_price = $this->getPriceFromPercentage($unit->price, $academic_level->percentage);
        $author_level_price   = $this->getPriceFromPercentage($unit->price, $author_level->percentage);

        $price = $unit->price + $subject_price + $academic_level_price + $author_level_price;

        $urgency = $unit->urgency;
        
        return [
            'amount'               => $price,
            'quantity'             => 1,
            'unit_price'           => $unit->price,
            'subject_price'        => $subject_price,
            'academic_level_price' => $academic_level_price,
            'author_level_price'   => $author_level_price,

            'number_of_words'      => $unit->quantity,
            'revisions_allowed'    => $assignment->number_of_revisions_allowed,
            'dead_line'            => get_urgency_date($urgency->type, $urgency->value, 'Y-m-d H:i:s'),
            'dead_line_for_author' => get_urgency_date($urgency->type_for_writer, $urgency->value_for_writer, 'Y-m-d H:i:s'),
        ];

    }

    private function getPriceFromPercentage($base_price, $percentage)
    {
        return ($base_price * $percentage) / 100;

    }

}
