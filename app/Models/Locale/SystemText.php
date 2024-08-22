<?php

namespace App\Models\Locale;

use Illuminate\Database\Eloquent\Model;

class SystemText extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'key',       
        'text',       
    ];
}
