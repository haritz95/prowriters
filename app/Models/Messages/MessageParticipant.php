<?php

namespace App\Models\Messages;

use App\Models\User;
use Illuminate\Support\Str;
use App\Models\Messages\MessageThread;
use Illuminate\Database\Eloquent\Model;

class MessageParticipant extends Model
{
    protected $fillable = [
        'uuid',
        'message_thread_id',
        'user_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        //'id',
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

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = (string) Str::orderedUuid();

        });

    }

    public function thread()
    {
        return $this->belongsTo(MessageThread::class, 'message_thread_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
