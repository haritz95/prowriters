<?php

namespace App\Models\ProjectManagement;

use App\Models\User;
use App\Traits\HasAttachment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class RevisionRequest extends Model
{

    use HasAttachment;

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = (string) Str::orderedUuid();
        });
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }

    protected $fillable = [
        'submitted_work_id',
        'user_id',
        'message',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function submittedWork()
    {
        return $this->belongsTo('App\Models\Orders\SubmittedWork', 'submitted_work_id', 'id');
    }
}
