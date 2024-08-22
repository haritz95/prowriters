<?php

namespace App\Models\Website\Samples;

use Illuminate\Database\Eloquent\Model;

class SampleFile extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'id',
        'title',
        'topic',
        'specifications',
        'attachment',
    ];

    protected $casts = [
        'specifications' => 'array',
    ];
}
