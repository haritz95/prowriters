<?php

namespace App\Models\Author;

use Illuminate\Database\Eloquent\Model;

class ApplicantStatus extends Model
{
    const APPLIED                       = 1;
    const REVIEWED                      = 2;
    const SCREEN                        = 3;
    const NEED_TO_SCHEDULE_AN_INTERVIEW = 4;
    const INTERVIEW_SCHEDULED           = 5;
    const INTERVIEWED                   = 6;
    const OFFER                         = 7;
    const HIRED                         = 8;
    const CANDIDATE_WITHDREW            = 9;
    const ON_HOLD                       = 10;
    const REJECT                        = 11;

    public static function getList()
    {
        return [
            ['id' => self::APPLIED, 'name' => 'Applied'],
            ['id' => self::REVIEWED, 'name' => 'Reviewed'],
            ['id' => self::SCREEN, 'name' => 'Screen'],
            ['id' => self::NEED_TO_SCHEDULE_AN_INTERVIEW, 'name' => 'Need to schedule an interview'],
            ['id' => self::INTERVIEW_SCHEDULED, 'name' => 'Interview scheduled'],
            ['id' => self::INTERVIEWED, 'name' => 'Interviewed'],
            ['id' => self::OFFER, 'name' => 'Offer'],
            ['id' => self::HIRED, 'name' => 'Hired'],
            ['id' => self::CANDIDATE_WITHDREW, 'name' => 'Candidate Withdrew'],
            ['id' => self::ON_HOLD, 'name' => 'On Hold'],
            ['id' => self::REJECT, 'name' => 'Reject'],
        ];
    }

}
