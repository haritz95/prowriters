<?php

namespace App\Services\ProjectManagement;

use App\Enums\ServiceType;
use App\Http\Requests\StoreBidRequest;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Business\AcademicLevel;
use App\Models\Business\AuthorLevel;
use App\Models\Business\GrammaticalPerson;
use App\Models\Business\Language;
use App\Models\Business\PaperFormat;
use App\Models\Business\Service;

class TaskDropdownService
{
    private $requestForm;
    private $isBiddingRequest;

    public function get(Service $service, $is_bidding = false)
    {
        if ($is_bidding) {
            $this->requestForm      = new StoreBidRequest();
            $this->isBiddingRequest = true;
        } else {
            $this->requestForm      = new StoreOrderRequest();
            $this->isBiddingRequest = false;
        }

        switch ($service->service_type_id) {
            case ServiceType::ACADEMIC_WRITING:
                return $this->dropdownForAcademicWriting($service);
                break;
            case ServiceType::CONTENT_WRITING:
                return $this->dropdownForContent($service);
                break;
            case ServiceType::FIXED_PRICE:
                return $this->dropdownForFixedPrice($service);
                break;
            default:
                return [];
                break;
        }

    }

    public function dropdownForContent(Service $service): array
    {
        $service->load(['subjects', 'assignments', 'assignments.units', 'assignments.units.urgency', 'additionalServices']);

        foreach ($service->assignments as $assignment) {

            foreach ($assignment->units as $key => $unit) {
                $assignment->units[$key]['name'] = $unit->name . ' (' . __('Turnaround Time') . ' ' . $unit->urgency->name . ')';

                $data['assignment_units'][$unit->id] = $unit;
            }

            $data['units'][$assignment->id] = $assignment->units;

        }

        foreach ($service->subjects as $subject) {
            $data['subjects'][$subject->id] = $subject;
        }

        $languages = Language::get();

        foreach ($languages as $language) {
            $data['content_languages'][$language->id] = $language;
        }

        $author_levels = AuthorLevel::orderBy('numeric_value', 'ASC')->get();

        if ($this->isBiddingRequest) {

            $data['author_levels'] = $this->modifyAuthorLevelForBidding($author_levels);
        } else {
            $data['author_levels'] = $author_levels;

            foreach ($author_levels as $author_level) {
                $data['service_author_levels'][$author_level->id] = $author_level;
            }
        }

        $data['service']            = $service;
        $data['author_levels']      = $author_levels;
        $data['grammatical_people'] = GrammaticalPerson::get();
        $data['languages']          = $languages;

        $data['texts'] = [
            'Free'                 => __('Free'),
            'something_went_wrong' => __('Something went wrong please try again later'),
        ];

        // Prepare the fields for form
        $fields = array_fill_keys(array_keys($this->requestForm->contentWritingFields()), null);

        $assignment_id = $service->assignments->first()->id;
        $units         = $data['units'][$assignment_id];

        $data['fields'] = array_merge($fields, [
            'assignment_id'   => $assignment_id,
            'unit_id'         => $units->first()->id,
            'subject_id'      => $service->subjects->first()->id,
            'language_id'     => $data['languages']->first()->id,
            'author_level_id' => $data['author_levels']->first()->id,

        ]);

        return $data;
    }

    public function dropdownForAcademicWriting(Service $service): array
    {
        $service->load(['subjects', 'assignments', 'assignments.units', 'assignments.units.urgency', 'additionalServices']);

        foreach ($service->assignments as $assignment) {

            foreach ($assignment->units as $key => $unit) {
                $assignment->units[$key]['name'] = $unit->name . ' (' . __('Turnaround Time') . ' ' . $unit->urgency->name . ')';

                $data['assignment_units'][$unit->id] = $unit;
            }

            $data['units'][$assignment->id] = $assignment->units;

        }

        foreach ($service->subjects as $subject) {
            $data['subjects'][$subject->id] = $subject;
        }

        $author_levels = AuthorLevel::orderBy('numeric_value', 'ASC')->get();

        foreach ($author_levels as $author_level) {
            $data['service_author_levels'][$author_level->id] = $author_level;
        }

        if ($this->isBiddingRequest) {

            $data['author_levels'] = $this->modifyAuthorLevelForBidding($author_levels);
        } else {
            $data['author_levels'] = $author_levels;
        }

        $academic_levels = AcademicLevel::orderBy('id', 'ASC')->get();

        foreach ($academic_levels as $academic_level) {
            $data['service_academic_levels'][$academic_level->id] = $academic_level;
        }

        $data['service']         = $service;
        $data['academic_levels'] = $academic_levels;
        $data['paper_formats']   = PaperFormat::orderBy('id', 'ASC')->get();

        // Prepare the fields for form
        $fields = array_fill_keys(array_keys($this->requestForm->academicWritingFields()), null);

        $assignment_id = $service->assignments->first()->id;
        $units         = $data['units'][$assignment_id];
        // $data['default_units_dropdown'] = $units;

        $data['fields'] = array_merge($fields, [
            'assignment_id'     => $assignment_id,
            'unit_id'           => $units->first()->id,
            'subject_id'        => $service->subjects->first()->id,
            'author_level_id'   => $data['author_levels']->first()->id,
            'academic_level_id' => $data['academic_levels']->first()->id,
            'paper_format_id'   => $data['paper_formats']->first()->id,
        ]);

        return $data;
    }

    public function dropdownForFixedPrice(Service $service): array
    {
        $service->load(['assignments', 'additionalServices']);

        foreach ($service->assignments as $assignment) {
            $data['service_assignments'][$assignment->id] = $assignment;

        }

        // Prepare the fields for form
        $fields = array_fill_keys(array_keys($this->requestForm->fixedPriceFields()), null);

        $data['service'] = $service;
        $data['fields']  = array_merge($fields, [
            'assignment_id' => $service->assignments->first()->id,

        ]);

        if ($this->isBiddingRequest) {
            $author_levels                     = AuthorLevel::orderBy('numeric_value', 'ASC')->get();
            $data['author_levels']             = $this->modifyAuthorLevelForBidding($author_levels);
            $data['fields']['author_level_id'] = $data['author_levels']->first()->id;
        }
        $data['quantity'] = 1;
        // if ($this->isBiddingRequest) {
        //     $data['author_levels'] = AuthorLevel::orderBy('numeric_value', 'ASC')->get();

        //     $data['author_levels']->prepend(((object) ['id' => null, 'name' => __('Any'), 'description' => __('Any author can bid on the task')]));

        //     $data['fields']['author_level_id'] = $data['author_levels']->first()->id;
        // }

        return $data;
    }

    private function modifyAuthorLevelForBidding($author_levels)
    {
        $item = ((object) ['id' => null, 'name' => __('Any'), 'description' => __('Any author can bid on the task')]);
        $author_levels->prepend($item);
        return $author_levels;
    }
}
