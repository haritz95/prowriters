<?php

namespace App\Models\ProjectManagement;

use App\Models\Business\AcademicLevel;
use App\Models\Business\Assignment;
use App\Models\Business\PaperFormat;
use App\Models\Business\Subject;
use App\Traits\HasTask;
use Illuminate\Database\Eloquent\Model;

class AcademicWriting extends Model
{
    use HasTask;

    public $timestamps = false;

    protected $fillable = [
        'assignment_id',
        'subject_id',
        'academic_level_id',
        'number_of_words',

        'paper_format_id',
        'number_of_sources',
        'instruction',

        // Financial
        'quantity',
        'amount',
        'unit_price',
        'academic_level_price',
        'subject_price',
        'author_level_price',

    ];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function academicLevel()
    {
        return $this->belongsTo(AcademicLevel::class);
    }

    public function paperFormat()
    {
        return $this->belongsTo(PaperFormat::class);
    }

    public function scopeGetFields($q)
    {
        $result = $q->with(['subject', 'assignment', 'academicLevel', 'paperFormat', 'task.service'])
            ->get()->first();

        $meta = [];

        $this->createRow($meta, __('Service'), $result->task->service->name);
        $this->createRow($meta, __($result->task->service->assignment_label), $result->assignment->name);        
        $this->createRow($meta, __('Number of words'), $result->number_of_words);
        $this->createRow($meta, __('Academic Level'), $result->academicLevel->name);
        $this->createRow($meta, __('Paper Format'), $result->paperFormat->name);
        $this->createRow($meta, __('Number of sources'), $result->number_of_sources);

        $fields = [];

        $this->createRow($fields, __('Instruction'), $result->instruction);

        return ['meta' => $meta, 'fields' => $fields];

    }

    public function scopeGetFinancialFields($q)
    {
        $result = $q->get()->first();

        $meta = [];

        $this->createRow($meta, __('Unit Price'), $result->unit_price);
        $this->createRow($meta, __('Subject Price'), $result->subject_price);
        $this->createRow($meta, __('Academic Level'), $result->academic_level_price);
        $this->createRow($meta, __('Author Level Price'), $result->author_level_price);

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
