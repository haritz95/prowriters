<?php

namespace App\Models\Messages;

use App\Models\Messages\MessageThread;
use App\Models\User;
use App\Traits\HasAttachment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Message extends Model
{
    use HasAttachment;

    protected $fillable = [
        'uuid',
        'message_thread_id',
        'user_id',
        'body',
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

        self::created(function ($model) {
            $model->thread->updated_at = now();
            $model->thread->save();
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
