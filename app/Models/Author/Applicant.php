<?php

namespace App\Models\Author;

use App\Models\Author\ApplicantStatus;
use App\Models\Business\Service;
use App\Models\Country;
use App\Traits\HasAttachment;
use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    use HasAttachment;

    protected $fillable = [
        'uuid',
        'number',
        'applicant_status_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'country_code',
        'timezone',
        'education_level_id',
        'bio',
        'address',
        'city',
        'state',
        'blog_url',
        'online_portfolio_url',
        'linked_in_url',
        'years_of_experience',
        'language_id_1',
        'language_id_2',
        'service_id_1',

        'subject_id_1',
        'subject_id_2',
        'subject_id_3',
        'subject_id_4',
        'subject_id_5',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id',
    ];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function status()
    {
        return $this->belongsTo(ApplicantStatus::class, 'applicant_status_id');
    }

    public function educationLevel()
    {
        return $this->belongsTo(EducationLevel::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id_1');
    }

    public function subject_1()
    {
        return $this->belongsTo('App\Models\Business\Subject', 'subject_id_1');
    }

    public function subject_2()
    {
        return $this->belongsTo('App\Models\Business\Subject', 'subject_id_2');
    }

    public function subject_3()
    {
        return $this->belongsTo('App\Models\Business\Subject', 'subject_id_3');
    }

    public function subject_4()
    {
        return $this->belongsTo('App\Models\Business\Subject', 'subject_id_4');
    }
    public function subject_5()
    {
        return $this->belongsTo('App\Models\Business\Subject', 'subject_id_5');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_code', 'code');
    }

    public static function adminSearchDropdown()
    {
        $data['statuses'] = ApplicantStatus::orderBy('id', 'ASC')->get()->prepend(['id' => '', 'name' => __('All')]);

        return $data;
    }
}
