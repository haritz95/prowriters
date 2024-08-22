<?php

namespace App\Models\ProjectManagement;

use App\Models\Business\Assignment;
use App\Traits\HasTask;
use Illuminate\Database\Eloquent\Model;

class FixedPriceWriting extends Model
{
    use HasTask;

    public $timestamps = false;

    protected $fillable = [
        'assignment_id',
        'instruction',
        //Financial
        'quantity',
        'amount',
    ];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    public function scopeGetFields($q)
    {
        $result = $q->with(['assignment', 'task.service'])->get()->first();

        $meta = [];

        $this->createRow($meta, __('Service'), $result->task->service->name);
        $this->createRow($meta, __($result->task->service->assignment_label), $result->assignment->name);
        $this->createRow($meta, __('Quantity'), $result->quantity);

        $fields = [];

        $this->createRow($fields, __('Instructions'), $result->instruction);

        return ['meta' => $meta, 'fields' => $fields];

    }


    public function scopeGetFinancialFields($q)
    {
        $result = $q->get()->first();

        $meta = [];

        $this->createRow($meta, __('Unit Price'), $result->amount);     

        $fields = [
            'quantity' => $result->quantity,
            'amount'   => $result->amount,
        ];

        return ['meta' => $meta, 'fields' => $fields];

    }

    private function createRow(&$array, $label, $value)
    {
        array_push($array, ['label' => $label, 'value' => $value]);
    }
}
