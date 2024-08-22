<?php

namespace App\Models\Messages;

use App\Models\Messages\Message;
use App\Models\Messages\MessageParticipant;
use App\Models\NumberGenerator;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MessageThread extends Model
{
    protected $fillable = [
        'uuid',
        'subject',
        'user_id',
        'recipient_id',
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
            $model->uuid   = (string) Str::orderedUuid();
            $model->number = NumberGenerator::gen(self::class);
        });

    }

    public function participants()
    {
        return $this->hasMany(MessageParticipant::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id', 'id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    // public function scopeForUser($query, $userId)
    // {
    //     $participantsTable = Models::table('participants');
    //     $threadsTable      = Models::table('threads');

    //     return $query->join($participantsTable, $this->getQualifiedKeyName(), '=', $participantsTable . '.thread_id')
    //         ->where($participantsTable . '.user_id', $userId)
    //         ->whereNull($participantsTable . '.deleted_at')
    //         ->select($threadsTable . '.*');
    // }

    public function scopeForUserWithNewMessages($query, $userId)
    {
        $participantTable = 'message_participants';
        $threadsTable     = 'message_threads';

        return $query->join($participantTable, $this->getQualifiedKeyName(), '=', $participantTable . '.message_thread_id')
            ->where($participantTable . '.user_id', $userId)
        // ->whereNull($participantTable . '.deleted_at')
            ->where(function ($query) use ($participantTable, $threadsTable) {
                $query->where($threadsTable . '.updated_at', '>', $this->getConnection()->raw($this->getConnection()->getTablePrefix() . $participantTable . '.last_read'))
                    ->orWhereNull($participantTable . '.last_read');
            })
            ->select($threadsTable . '.*');
    }
}
