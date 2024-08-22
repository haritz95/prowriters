<?php

namespace App\Models\ProjectManagement;

use App\Models\User;
use Illuminate\Support\Str;
use App\Events\BidApprovedEvent;
use App\Enums\BidRequestStatusType;
use App\Models\ProjectManagement\Task;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProjectManagement\BidRequest;
use App\Models\ProjectManagement\TaskStatus;

class Bid extends Model
{
    protected $fillable = [
        'uuid',
        'bid_request_id',
        'author_id',
        'duration_days',
        'number_of_revisions',
        'total',
        'author_payment_amount',
        'platform_commission_rate',

    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = Str::orderedUuid();
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

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

    public function bidRequest()
    {
        return $this->belongsTo(BidRequest::class, 'bid_request_id', 'id');
    }

    public static function handleSuccessfulPaymentForBid($bid_id, $task_id)
    {
        $bid  = self::find($bid_id);
        $task = Task::find($task_id);

        $delivery_date = get_urgency_date('days', $bid->duration_days, 'Y-m-d H:i:s');

        // Update Task Information
        $task->author_id             = $bid->author_id;
        $task->total                 = $bid->total;
        $task->author_payment_amount = $bid->author_payment_amount;
        $task->task_status_id        = TaskStatus::NEW;
        $task->dead_line             = $delivery_date;
        $task->dead_line_for_author  = $delivery_date;
        $task->revisions_allowed     = $bid->number_of_revisions;
        $task->save();

        //Dispatching Event
        event(new BidApprovedEvent($task));

        // Close bid
        BidRequest::where('id', $bid->bid_request_id)->update(['bid_request_status_id' => BidRequestStatusType::HIRED]);
    }

}
