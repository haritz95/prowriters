<?php

namespace App\Models\Business;

use Illuminate\Database\Eloquent\Model;

class PaperType extends Model
{

    protected $fillable = [
        'id',
        'name',        
        'inactive'
    ];   
}
