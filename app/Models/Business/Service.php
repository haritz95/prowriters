<?php

namespace App\Models\Business;

use App\Enums\ServiceType;
use App\Models\Business\AdditionalService;
use App\Models\Business\Assignment;
use App\Models\Business\Subject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Service extends Model
{
    protected $fillable = [
        'slug',
        'service_type_id',
        'assignment_label',
        'name',
        'description',
        'image',
        'unit_name',
        'minimum_order_quantity',
        'maximum_file_size',
        'maximum_number_of_files_to_upload',
        'allowed_file_extensions',
        'inactive',
        'not_available_for_direct_order',
        'not_available_for_bidding',
        'commission',
        'commission_from_bid',
    ];

    protected $casts = [
        'inactive' => 'boolean',
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
        return 'slug';
    }

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = (string) Str::orderedUuid();
            $model->slug = Str::of($model->name)->slug('-');
        });

        self::updating(function ($model) {
            $model->slug = Str::of($model->name)->slug('-');
        });
    }

    public function scopeActive($query)
    {
        return $query->where('inactive', false)->orWhereNull('inactive');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'service_subject', 'service_id', 'subject_id');
    }

    public function additionalServices()
    {
        return $this->belongsToMany(AdditionalService::class, 'service_additional_service', 'service_id', 'additional_service_id');
    }

    public static function asDropdown()
    {
        return self::select(['id', 'name'])->active()->get()->toArray();
    }

    public static function getBusinessMenuItemsByService(Service $service)
    {
        if ($service->id == ServiceType::ACADEMIC_WRITING) {
            return [
                [
                    "name"        => __($service->assignment_label),
                    "create"      => route("admin.assignments.create", $service->slug),
                    "list"        => route("admin.assignments.index", $service->slug),
                    "route_group" => "admin.assignments",
                ],
                [
                    "name"        => __("Academic Levels"),
                    "create"      => route("admin.academicLevels.create", $service->slug),
                    "list"        => route("admin.academicLevels.index", $service->slug),
                    "route_group" => "admin.academicLevels",
                ],
                [
                    "name"        => __("Paper Formats"),
                    "create"      => route("admin.paperFormats.create", $service->slug),
                    "list"        => route("admin.paperFormats.index", $service->slug),
                    "route_group" => "admin.paperFormats",
                ],
            ];
        } else if ($service->id == ServiceType::CONTENT_WRITING) {
            return [
                [
                    "name"        => __($service->assignment_label),
                    "create"      => route("admin.assignments.create", $service->slug),
                    "list"        => route("admin.assignments.index", $service->slug),
                    "route_group" => "admin.assignments",
                ],

                [
                    "name"        => __("Languages"),
                    "create"      => route("admin.languages.create", $service->slug),
                    "list"        => route("admin.languages.index", $service->slug),
                    "route_group" => "admin.languages",
                ],
                [
                    "name"        => __("Grammatical People"),
                    "create"      => route("admin.grammaticalPeople.create", $service->slug),
                    "list"        => route("admin.grammaticalPeople.index", $service->slug),
                    "route_group" => "admin.grammaticalPeople",
                ],

            ];
        } else {
            return [
                [
                    "name"        => __($service->assignment_label),
                    "create"      => route("admin.packages.create", $service->slug),
                    "list"        => route("admin.packages.index", $service->slug),
                    "route_group" => "admin.packages",
                ],
            ];
        }
    }

}
