<?php
namespace App\Models\Billing;

use App\Models\Billing\Bill;
use App\Models\ProjectManagement\Task;
use Illuminate\Database\Eloquent\Model;

class BillItem extends Model
{

    public $timestamps = false;

    protected $fillable = [
        'task_id',
        'total',
        'bill_id',
    ];

    public function bill()
    {
        return $this->belongsTo(Bill::class);
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
