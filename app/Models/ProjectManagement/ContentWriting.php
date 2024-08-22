<?php

namespace App\Models\ProjectManagement;

use App\Models\Business\Assignment;
use App\Models\Business\GrammaticalPerson;
use App\Models\Business\Language;
use App\Models\Business\Subject;
use App\Traits\HasTask;
use Illuminate\Database\Eloquent\Model;

class ContentWriting extends Model
{
    use HasTask;

    public $timestamps = false;

    protected $fillable = [
        'assignment_id',
        'subject_id',
        'language_id',
        'number_of_words',

        'content_goals',
        'grammatical_person_id',
        'target_audience',
        'target_keywords',
        'links_to_example_content',
        'style_and_tone',
        'structure_and_formatting_requirements',
        'referencing_and_linking_preferences',
        'things_to_avoid',
        'additional_notes',

        //Financial
        'quantity',
        'amount',
        'unit_price',
        'subject_price',
        'language_price',
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

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function grammaticalPerson()
    {
        return $this->belongsTo(GrammaticalPerson::class);
    }

    public function scopeGetFields($q)
    {
        $result = $q->with(['subject', 'assignment', 'language', 'grammaticalPerson', 'task.service'])
            ->get()->first();

        $meta = [];

        $this->createRow($meta, __('Service'), $result->task->service->name);
        $this->createRow($meta, __($result->task->service->assignment_label), $result->assignment->name);
       
        $this->createRow($meta, __('Number of words'), $result->number_of_words);
        $this->createRow($meta, __('Subject'), $result->subject->name);
        $this->createRow($meta, __('Language'), $result->language->name);

        $fields = [];

        $this->createRow($fields, __('Content Goals & Things to Mention'), $result->content_goals);
        $this->createRow($fields, __('Grammatical Person'), ($result->grammaticalPerson) ? $result->grammaticalPerson->name : null);
        $this->createRow($fields, __('Target Audience'), $result->target_audience);
        $this->createRow($fields, __('Target Keywords'), $result->target_keywords);
        $this->createRow($fields, __('Links to Example Content'), $result->links_to_example_content);
        $this->createRow($fields, __('Style & Tone'), $result->style_and_tone);
        $this->createRow($fields, __('Structure & Formatting Requirements'), $result->structure_and_formatting_requirements);
        $this->createRow($fields, __('Referencing & Linking Preferences'), $result->referencing_and_linking_preferences);
        $this->createRow($fields, __('Things to Avoid'), $result->things_to_avoid);
        $this->createRow($fields, __('Additional Notes'), $result->additional_notes);

        return ['meta' => $meta, 'fields' => $fields];

    }

    public function scopeGetFinancialFields($q)
    {
        $result = $q->get()->first();

        $meta = [];

        $this->createRow($meta, __('Unit Price'), $result->unit_price);
        $this->createRow($meta, __('Subject Price'), $result->subject_price);
        $this->createRow($meta, __('Language'), $result->language_price);
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
