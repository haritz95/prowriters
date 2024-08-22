<?php

namespace App\Models\Author;

use App\Models\Business\AuthorLevel;
use App\Models\Author\EducationLevel;
use Illuminate\Database\Eloquent\Model;

class AuthorProfile extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'author_level_id',
        'education_level_id',
        'years_of_experience',
        'bio',
        'address',
        'city',
        'state',
        'payment_method',
        'payment_method_details',
        'blog_url',
        'online_portfolio_url',
        'linked_in_url',
        'language_id_1',
        'language_id_2',
        'service_id_1',
        'service_id_2',
        'service_id_3',
        'subject_id_1',
        'subject_id_2',
        'subject_id_3',
        'subject_id_4',
        'subject_id_5',
    ];

    public function basicInformation()
    {
        return $this->belongsTo(User::class);
    }

    public function educationLevel()
    {
        return $this->belongsTo(EducationLevel::class);
    }

    public function authorLevel()
    {
        return $this->belongsTo(AuthorLevel::class);
    }

    public static function experience()
    {
        foreach (range(1, 20) as $year) {
            $data[] = ['id' => $year, 'name' => $year . ' +'];
        }
        return $data;
    }

    
}
