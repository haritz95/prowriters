<?php

namespace App\Models\Accounting;

use App\Models\Accounting\Invoice;
use App\Models\ProjectManagement\Task;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class InvoiceItem extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'invoice_id',
        'invoiceable_type',
        'invoiceable_id',
        'name',
        'description',
        'price',
        'quantity',
        'sub_total',
    ];

    /**
     * Get the parent invoiceable model
     */
    public function invoiceable()
    {
        Relation::morphMap([
            'task' => Task::class,
        ]);

        return $this->morphTo();
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

}
