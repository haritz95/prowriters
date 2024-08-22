<?php

namespace App\Models\ProjectManagement;

use App\Enums\SpacingType;
use App\Enums\UnitType;
use App\Models\Business\AdditionalService;
use App\Models\Business\AuthorLevel;
use App\Models\Business\Service;
use App\Models\Business\ServiceLevel;
use App\Models\Business\Subject;
use App\Models\NumberGenerator;
use App\Models\ProjectManagement\BidRequest;
use App\Models\ProjectManagement\Project;
use App\Models\ProjectManagement\Rating;
use App\Models\ProjectManagement\SubmittedWork;
use App\Models\ProjectManagement\TaskContent;
use App\Models\ProjectManagement\TaskStatus;
use App\Models\User;
use App\Traits\HasAttachment;
use App\Traits\HasComment;
use App\Traits\InvoiceTrait;
use App\Traits\TagOperation;
use App\Traits\WhereDateBetweenTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Task extends Model
{
    use SoftDeletes, TagOperation, HasAttachment, HasComment, InvoiceTrait, WhereDateBetweenTrait;

    protected $dates = [
        'deleted_at',
        'created_at',
        'dead_line',
        'dead_line_for_author',
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

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'update_via_sms'      => 'boolean',
        'is_total_overridden' => 'boolean',
    ];

    protected $fillable = [
        'uuid',
        'number',
        'title',
        'project_id',
        'task_status_id',
        'customer_id',
        'service_id',

        'author_level_id',
        'dead_line',
        'dead_line_for_author',
        'author_id',
        'update_via_sms',
        'revisions_allowed',
        'revisions_taken',

        // Financial
        'additional_services_price',
        'is_total_overridden',
        'original_total',
        'total',
        'author_payment_amount',
        'user_id',
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->number = NumberGenerator::gen(self::class);
            $model->uuid   = (string) Str::orderedUuid();

        });
    }

    public function details(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'details_type', 'details_id');
    }

    public function status()
    {
        return $this->belongsTo(TaskStatus::class, 'task_status_id', 'id');
    }

    public function content()
    {
        return $this->hasOne(TaskContent::class, 'task_id', 'id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function externalDiscussions()
    {
        return $this->hasMany(TaskMessage::class)->where('is_public', 1);
    }

    public function internalDiscussions()
    {
        return $this->hasMany(TaskMessage::class)->whereNull('is_public');
    }

    public function serviceLevel()
    {
        return $this->belongsTo(ServiceLevel::class);
    }

    public function walletPayment()
    {
        $transaction = $this->walletTransactions()->get();

        if ($transaction->count() > 0) {
            return $this->walletTransactions()->get()->first()->pivot;
        }
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'followers', 'task_id', 'user_id');
    }

    public function isAFollower($user_id)
    {
        return $this->followers()->where('user_id', $user_id)->exists();
    }

    public function additionalServices()
    {
        return $this->belongsToMany(AdditionalService::class, 'task_additional_services', 'task_id', 'additional_service_id')
            ->withPivot('quantity', 'price');
    }

    public function service()
    {
        return $this->hasOne('App\Models\Business\Service', 'id', 'service_id');
    }

    // public function assignee()
    // {
    //     return $this->belongsTo('App\Models\User', 'author_id', 'id');
    // }

    public function authorLevel()
    {
        return $this->belongsTo(AuthorLevel::class, 'author_level_id', 'id');
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\User', 'customer_id', 'id');
    }

    public function editor()
    {
        return $this->hasOne(User::class, 'id', 'editor_id');
    }

    public function author()
    {
        return $this->hasOne(User::class, 'id', 'author_id');
    }

    public function rating()
    {
        return $this->hasOne(Rating::class);
    }

    public function bidRequest()
    {
        return $this->hasOne(BidRequest::class);
    }

    public function submittedWorks()
    {
        return $this->hasMany(SubmittedWork::class);
    }

    public static function adminDropdown()
    {

        $data['statuses'] = TaskStatus::orderBy('id', 'ASC')->get()->prepend(['id' => '', 'name' => __('All')]);
        $data['services'] = Service::orderBy('id', 'ASC')->get()->prepend(['id' => '', 'name' => __('All')]);
        $data['subjects'] = Subject::orderBy('id', 'ASC')->get()->prepend(['id' => '', 'name' => __('All')]);
        // $data['service_levels'] = ServiceLevel::orderBy('id', 'ASC')->get()->prepend(['id' => '', 'name' => __('All')]);
        $data['author_levels'] = AuthorLevel::orderBy('id', 'ASC')->get()->prepend(['id' => '', 'name' => __('All')]);

        $data['order_by_options'] = [
            ['id' => 'nearest_due_date', 'name' => __('Nearest due date')],
            ['id' => 'created_date', 'name' => __('Create date')],
            ['id' => 'task_total_high', 'name' => __('Task Total High')],
            ['id' => 'task_total_low', 'name' => __('Task Total Low')],
        ];
        return $data;
    }

    public static function authorDropdown()
    {

        $data['statuses'] = TaskStatus::orderBy('id', 'ASC')
            ->whereIn('id', self::taskStatusAllowedForAuthor())->get()->prepend(['id' => '', 'name' => __('All')]);

        $data['deadlines'] = [
            ['id' => '', 'name' => __('N/A')],
            ['id' => 'today', 'name' => __('Today')],
            ['id' => 'tomorrow', 'name' => __('Tomorrow')],
            ['id' => 'day_after_tomorrow', 'name' => __('The day after tomorrow')],
        ];

        return $data;
    }

    public static function browseWorkDropdown()
    {
        $data['services_list'] = [
            '' => __('All'),
        ] + Service::orderBy('id', 'ASC')->pluck('name', 'id')->toArray();

        $data['task_subject_list'] = [
            '' => __('All'),
        ] + Subject::pluck('name', 'id')->toArray();

        return $data;
    }

    public static function customerDropdown()
    {
        $data['statuses'] = TaskStatus::orderBy('id', 'ASC')->get()->prepend(['id' => '', 'name' => __('All')]);
        // $data['services'] = Service::orderBy('id', 'ASC')->get()->prepend(['id' => '', 'name' => __('All')]);
        return $data;
    }

    // private static function getWorkTypesByServices(Collection $services)
    // {
    //     $work_types = [];

    //     foreach ($services as $service) {
    //         if ($service->id != ServiceType::RESUME_WRITING) {
    //             $work_types[$service->id] = WorkType::dropdownByServiceSettings($service->disable_writing, $service->disable_editing, $service->disable_proofreading);
    //         }
    //     }
    //     return $work_types;

    // }

    /*
    public static function dropdown()
    {
    $services = Service::active()->with(['subjects', 'assignments', 'additionalServices'])->get();

    $data['services']           = $services->toArray();
    $data['urgencies']          = Urgency::orderBy('id', 'ASC')->get();
    $data['academic_levels']    = AcademicLevel::orderBy('id', 'ASC')->get();
    $data['paper_formats']      = PaperFormat::orderBy('id', 'ASC')->get();
    $data['author_levels']      = AuthorLevel::orderBy('id', 'ASC')->get();
    $data['service_levels']     = ServiceLevel::orderBy('price', 'ASC')->get();
    $data['grammatical_people'] = GrammaticalPerson::get();

    $data['content_writing_languages'] = [];
    foreach (Language::get() as $language) {
    if ($language->available_for_content_writing) {
    $data['content_writing_languages'][] = $language;
    }

    }

    $data['units_for_writing_services'] = UnitType::dropdown();
    $data['work_types']                 = self::getWorkTypesByServices($services);
    $data['spacings_types']             = SpacingType::dropdown();

    $data['urls'] = [
    // 'add_to_cart'       => route('customer.tasks.store'),
    // 'task_page'         => route('customer.tasks.create'),
    // 'upload_attachment' => route('attachments.store'),
    // 'request_for_bid'   => route('customer.bidRequests.store'),
    // 'pay_later'         => route('customer.tasks.payLater'),
    ];

    $data['texts'] = [
    'Free'                 => __('Free'),
    'something_went_wrong' => __('Something went wrong please try again later'),
    ];

    $data['services_types'] = ServiceType::getForFrontEnd();

    return $data;
    }
     */

    public static function extendDeadlineOptions()
    {
        $minutes = __('Minutes');
        $hour    = __('Hour');
        $hours   = __('Hours');
        $days    = __('Days');
        return [
            ['id' => '30_m', 'name' => 30 . ' ' . $minutes],
            ['id' => '1_h', 'name' => 1 . ' ' . $hour],
            ['id' => '2_h', 'name' => 2 . ' ' . $hours],
            ['id' => '4_h', 'name' => 4 . ' ' . $hours],
            ['id' => '6_h', 'name' => 6 . ' ' . $hours],
            ['id' => '8_h', 'name' => 8 . ' ' . $hours],
            ['id' => '12_h', 'name' => 12 . ' ' . $hours],
            ['id' => '24_h', 'name' => 24 . ' ' . $hours],
            ['id' => '2_d', 'name' => 2 . ' ' . $days],
        ];
    }

    public static function taskStatusAllowedForAuthor()
    {
        return [
            TaskStatus::NEW ,
            TaskStatus::IN_PROGRESS,
            TaskStatus::SUBMITTED_FOR_APPROVAL,
            TaskStatus::REQUESTED_FOR_REVISION,
            TaskStatus::QA_REJECTED,
            TaskStatus::COMPLETE,
            TaskStatus::ON_HOLD,
        ];
    }
    public static function statistics($author_id = null)
    {
        $tasks = self::select('task_status_id', DB::raw('count(*) as total'))
            ->groupBy('task_status_id');

        if ($author_id) {
            $tasks->where('author_id', $author_id);
            $statuses = TaskStatus::whereIn('id', self::taskStatusAllowedForAuthor())->get();
        } else {
            $statuses = TaskStatus::get();
        }

        $tasks = $tasks->pluck('total', 'task_status_id');

        if ($statuses->count() > 0) {
            $statuses = $statuses->toArray();

            foreach ($statuses as $key => $status) {
                $statuses[$key]['value'] = (!isset($tasks[$status['id']])) ? 0 : $tasks[$status['id']];
            }
            array_push($statuses, [
                'name'     => __('Not Invoiced'),
                'color'    => '#17a2b8',
                'bg_color' => '#17a2b8',
                'value'    => self::whereNull('invoice_id')
                    ->where('task_status_id', '<>', TaskStatus::CANCELED)->count(),
            ]);

            $statuses = array_chunk($statuses, 6);
        }

        return $statuses;
    }

    public static function calculateWordPageCount($quantity, $unit_name, $spacing_type = null)
    {
        $pages      = 0;
        $words      = 0;
        $unit_types = UnitType::get();
        // If the unit name is per Word then convert it to page
        if ($unit_name == UnitType::WORD) {
            $pages = ceil($quantity / 275) . ' ' . $unit_types[UnitType::PAGE];
            $words = $quantity . ' ' . $unit_types[UnitType::WORD];
        }
        // If the unit name is per Page then convert it to Word
        if (($unit_name == UnitType::PAGE) && $spacing_type) {
            $number_of_words_per_page = SpacingType::listOfNumberOfWords()[$spacing_type];
            $words                    = $number_of_words_per_page * $quantity . ' ' . $unit_types[UnitType::WORD];
            $pages                    = $quantity . ' ' . $unit_types[UnitType::PAGE];
        }

        return $words . ' / ' . $pages;
    }

    // public function scopeWithDetails($query, array $load = [])
    // {

    //     if ($this->service_id == ServiceType::ACADEMIC_WRITING) {
    //         $related_models = ['details.assignment', 'details.subject', 'details.academicLevel', 'details.paperFormat'];

    //     } else if ($this->service_id == ServiceType::CONTENT_WRITING) {
    //         $related_models = ['details.assignment', 'details.subject', 'details.language', 'details.grammaticalPerson'];

    //     } else if ($this->service_id == ServiceType::RESUME_WRITING) {
    //         $related_models = ['details.assignment'];

    //     }

    //     $this->load(array_merge([
    //         'status', 'service', 'urgency', 'serviceLevel', 'authorLevel', 'additionalServices', 'details',
    //     ], $load, $related_models));

    //     return $this;
    // }

    public function scopeLoadLatestSubmittedWork()
    {
        $this->load([
            'status',
            'submittedWorks'             => function ($q) {
                $q->latest('created_at')->first();
            },
            'submittedWorks.attachments' => function ($q) {
                $q->selectAll();
            },
            'submittedWorks.user'        => function ($q) {
                $q->basicInfo();
            },
            'rating',
        ]);

        if ($this->task_status_id == TaskStatus::SUBMITTED_FOR_APPROVAL) {
            $this->allowed_to_accept_or_revise = true;
        }

        if ($this->task_status_id == TaskStatus::COMPLETE && !$this->rating) {
            $this->allow_customer_to_submit_rating = true;
        }
        if ($this->task_status_id == TaskStatus::COMPLETE && $this->rating) {
            $this->show_rating = true;
        }
    }

    public function scopeArchiveForCustomer($query, $withArchive)
    {
        if (!filter_var($withArchive, FILTER_VALIDATE_BOOLEAN)) {
            return $query->whereNull('is_archived_for_customer');
        }
        return $query;
    }

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

    public function scopeTaskOrderBy($query, $orderBy)
    {
        switch ($orderBy) {
            case 'nearest_due_date':
                $query->whereNotIn('task_status_id', [TaskStatus::COMPLETE, TaskStatus::ON_HOLD, TaskStatus::CANCELED])->orderBy('dead_line', 'ASC');
                break;
            case 'created_date':
                $query->orderBy('created_at', 'ASC');
                break;
            case 'task_total_high':
                $query->orderBy('total', 'DESC');
                break;
            case 'task_total_low':
                $query->orderBy('total', 'ASC');
                break;
            default:
                # code...
                break;
        }

        return $query;
    }

    // public static function resetInformationForBidding($idOrIds)
    // {
    //     if (!is_array($idOrIds)) {
    //         $idOrIds = [$idOrIds];
    //     }

    //     self::whereIn('id', $idOrIds)->update([
    //         'task_status_id'        => NULL,
    //         'dead_line'             => null,
    //         'dead_line_for_author'  => null,
    //         'total'                 => 0,
    //         'author_payment_amount' => 0,
    //     ]);

    // }

    public static function getEligibleAuthors($task_id)
    {
        return User::select(['id', 'first_name', 'last_name', 'email'])
            ->whereIn('id', function ($query) use ($task_id) {
                $query->select('user_id')
                    ->from('author_profiles')
                    ->whereRaw('author_profiles.author_level_id IN (
                SELECT id FROM author_levels WHERE numeric_value >= (
                    SELECT author_levels.numeric_value FROM tasks
                    LEFT JOIN author_levels ON tasks.author_level_id = author_levels.id
                    WHERE tasks.id = ?
                )
            )', [$task_id]);
            })->get();

    }
}
