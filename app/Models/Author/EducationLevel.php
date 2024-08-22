<?php

namespace App\Models\Author;

use Illuminate\Database\Eloquent\Model;

class EducationLevel extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name'
    ];
}
