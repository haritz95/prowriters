<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferralSource extends Model
{
    public $timestamps = false;

    protected $fillable = [        
        'name',       
    ];
}
