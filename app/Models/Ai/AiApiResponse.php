<?php

namespace App\Models\Ai;

use Illuminate\Database\Eloquent\Model;

class AiApiResponse extends Model
{
    protected $fillable = [
        'user_id',
        'prompt_tokens',
        'completion_tokens',
        'total_tokens',
    ];

}
