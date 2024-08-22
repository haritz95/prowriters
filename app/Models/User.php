<?php

namespace App\Models;

use DateTimeZone;
use App\Enums\UserType;
use App\Traits\AuthorTrait;
use App\Traits\TagOperation;
use App\Enums\PermissionType;
use App\Traits\HasAttachment;
use App\Models\CustomerProfile;
use App\Traits\Wallet\HasWallet;
use Laravel\Sanctum\HasApiTokens;
use App\Models\ProjectManagement\Task;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use App\Models\ProjectManagement\TaskStatus;
use Spatie\Activitylog\Traits\CausesActivity;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Translation\HasLocalePreference;

class User extends Authenticatable implements MustVerifyEmail, HasLocalePreference
{
    // use SoftDeletes;
    use Notifiable, HasRoles, TagOperation, HasWallet;
    use CausesActivity, HasApiTokens, HasFactory, AuthorTrait, HasAttachment;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'code',
        'first_name',
        'last_name',
        'email',
        'password',
        'phone',
        'timezone',
        'country_code',
        'is_subscribed',
        'email_verified_at',
        'google_id',
        'facebook_id',
        'twitter_id',
        'linkedin_id',
        'language',
        'inactive',
    ];

    protected $appends = ['full_name', 'small_avatar'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at'     => 'datetime',
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

    public function preferredLocale()
    {
        return $this->language;
    }

    public function getFullNameAttribute()
    {
        return ucfirst($this->first_name) . ' ' . ucfirst($this->last_name);
    }
    public function getNameAttribute()
    {
        return ucfirst($this->first_name) . ' ' . ucfirst($this->last_name);
    }

    public function getSmallAvatarAttribute()
    {
        return ($this->photo) ? asset(Storage::url($this->photo)) : asset('images/user-placeholder.jpg');
    }

    public function scopeIncludeActive($query, bool $include_inactive_users = false)
    {
        if (!$include_inactive_users) {
            $query->active();
        }
        return $query;
    }

    public function scopeAuthors($query, bool $include_inactive_users = false)
    {
        return $query->where('type', UserType::AUTHOR)->includeActive($include_inactive_users);
    }

    public function scopeCustomers($query, bool $include_inactive_users = false)
    {
        return $query->where('type', UserType::CUSTOMER)->includeActive($include_inactive_users);
    }

    public function scopeAdmins($query, bool $include_inactive_users = false)
    {
        return $query->where('type', UserType::ADMIN)->includeActive($include_inactive_users);

    }
    
    public function scopeForDropdown($query, bool $include_inactive_users = false)
    {
        
        return $query->select(['id', 'first_name', 'last_name']);

    }

    public function scopeEditors($query)
    {
        return $query->role(PermissionType::ROLE_EDITOR)->active();
    }

    public function scopeActive($query)
    {
        return $query->where('inactive', false)->orWhereNull('inactive');
    }

    public function scopeBasicInfo($query)
    {
        return $query->select(['id', 'uuid', 'type', 'first_name', 'last_name', 'code', 'photo']);
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_code', 'code');
    }

    public function pushNotification()
    {
        return $this->hasOne('App\Models\PushNotification');
    }

    public function customerProfile()
    {
        return $this->hasOne(CustomerProfile::class, 'user_id', 'id');
    }

    public function customerTasks()
    {
        return $this->hasMany(Task::class, 'customer_id', 'id')->whereNot('task_status_id', TaskStatus::CANCELED);
    }

    public function isCustomer()
    {
        return $this->type == UserType::CUSTOMER;
    }

    public static function getTimeZones()
    {
        $timezone_identifiers = DateTimeZone::listIdentifiers(DateTimeZone::ALL);

        foreach ($timezone_identifiers as $r) {
            $data[] = ['id' => $r, 'name' => $r];
        }
        return $data;
    }

    public function scopeRetrieveAdminPermissions()
    {
        $this->permission_list = $this->getPermissionNames()
            ->flatMap(function ($values) {
                return [$values => true];
            });

        $this->permission_list['is_super_admin'] = $this->hasRole(PermissionType::ROLE_SUPER_ADMIN);

        return $this;
    }
}
