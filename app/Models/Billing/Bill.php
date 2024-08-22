<?php

namespace App\Models\Billing;

use App\Models\Billing\BillItem;
use App\Models\ProjectManagement\Task;
use App\Models\ProjectManagement\TaskStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bill extends Model
{
    use SoftDeletes;

    protected $casts = [
        'paid' => 'date',
    ];

    protected $fillable = [
        'uuid',
        'number',
        'invoice_number',
        'author_id',
        'total',
        'name',
        'address',
        'note',
        'paid',
        'payment_reference',
        'paid_by_user_id',
    ];

    public function scopeArchiveForAdmin($query, $withArchive)
    {
        if (!filter_var($withArchive, FILTER_VALIDATE_BOOLEAN)) {
            return $query->whereNull('is_archived_for_admin');
        }
        return $query;
    }

    public function scopeArchiveForAuthor($query, $withArchive)
    {
        if (!filter_var($withArchive, FILTER_VALIDATE_BOOLEAN)) {
            return $query->whereNull('is_archived_for_author');
        }
        return $query;
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

    public function items()
    {
        return $this->hasMany(BillItem::class);
    }

    public function from()
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

    public function getBillableTasks($user_id)
    {
        return Task::select('id', 'uuid', 'number', 'author_payment_amount')
            ->whereNull('is_billed')
            ->where('task_status_id', TaskStatus::COMPLETE)
            ->where('author_id', $user_id)
            ->get();
    }

    public function getTotalBillableAmount($tasks)
    {
        $total = 0;
        if ($tasks->count() > 0) {
            $total = $tasks->sum('author_payment_amount');
        }
        return $total;
    }

    public function scopeOutstandingPaymentAmount($query, $user_id)
    {
        return $query->where('author_id', $user_id)->whereNull('paid')->sum('total');

    }
}
