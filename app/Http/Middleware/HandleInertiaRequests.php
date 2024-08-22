<?php

namespace App\Http\Middleware;

use App\Enums\UserType;
use App\Models\ProjectManagement\TaskStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'layouts.app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request): array
    {
        // RewriteRule ^/?build/assets/(.*)$ /order/build/assets/$1

        // $firstLoadOnlyProps = $request->inertia() ? [] : [
        //     'main_menu' => (auth()->user()) ? MenuService::get(auth()->user()->type) : [],
        // ];
        $firstLoadOnlyProps = [];
        $authenticated      = [];

        if (auth()->check()) {
            $user          = auth()->user();
            $authenticated = [
                'auth_user'                           => [
                    'name'                                   => $user->full_name,
                    'id'                                     => $user->id,
                    'type'                                   => $user->type,
                    'edit_my_account_link'                   => $this->getEditMyAccountLink($user->type),
                    'role'                                   => ($user->type == UserType::ADMIN) ? $user->getRoleNames()->first() : null,
                    // 'permissions'                            => $this->getPermissions($user),
                    'urls'                                   => [
                        'image_manager' => get_base_url('filemanager?type=Images'),
                        'file_manager'  => get_base_url('filemanager?type=file'),
                    ],
                    'api_keys'                               => [
                        'tinymce' => get_tinymce_key(),
                    ],
                    'hide_countdown_timer_for_task_statuses' => [
                        TaskStatus::COMPLETE, TaskStatus::CANCELED,
                    ],
                    'is_self_assigning_tasks_enable'         => is_self_assigning_tasks_enable(),
                ],
                'version'                             => get_software_version(),
                'enable_instant_notification'         => true,
                'instant_notification_check_interval' => 30000, // 30 sec
            ];
        }

        return array_merge(parent::share($request), $firstLoadOnlyProps, $authenticated, [
            'base_url'                                    => get_base_url(),
            'rich_editor_base_url'                        => URL::to('/'),
            'company'                                     => [
                'name' => get_company_name(),
                'logo' => get_company_logo(),
            ],
            'flash'                                       => $this->getFlashMessage($request),
            // 'auth_layout_image'    => $this->getAuthLayoutImage(),
            'is_user_logged_in'                           => auth()->check(),
            // get_languages() is being loaded from language_helper.php file
            'system_languages'                            => get_languages(),
            'current_locale'                              => App::currentLocale(),
            'current_country_code'                        => get_country_code(App::currentLocale()),
            // 'business_operation_type' => ,
            'is_quality_control_enable'                   => is_quality_control_enable(),
            'is_bidding_enable'                           => is_bidding_enable(),
            'is_ordering_enable'                          => is_ordering_enable(),
            'hide_author_application_link_from_website'   => hide_author_application_link(),
            'disable_order_page_for_unauthenticated_user' => disable_order_page_for_unauthenticated_user(),
            'is_social_login_enable'                      => is_social_login_enable(),
            'is_single_language'                          => is_single_language(),
            'csrf_token'                                  => csrf_token(),

        ]);
    }

    private function getFlashMessage($request)
    {
        $data = null;

        if ($request->session()->has('success')) {
            $data = [
                'type'    => 'success',
                'title'   => __('Success'),
                'message' => $request->session()->get('success'),
            ];
        } else if ($request->session()->has('fail')) {
            $data = [
                'type'    => 'error',
                'title'   => __('Failed'),
                'message' => $request->session()->get('fail'),
            ];
        } else if ($request->session()->has('info')) {
            $data = [
                'type'    => 'info',
                'title'   => __('Information'),
                'message' => $request->session()->get('info'),
            ];
        }
        return $data;
    }

    private function getEditMyAccountLink($userType)
    {
        switch ($userType) {
            case UserType::AUTHOR:
                return route('author.account.edit');
                break;
            case UserType::CUSTOMER:
                return route('customer.account.edit');
                break;
            case UserType::ADMIN:
                return route('admin.account.edit');
                break;
            default:
                break;
        }
    }

    // private function getPermissions($user)
    // {
    //     $permissions = [];

    //     if ($user->type == UserType::ADMIN) {
    //         if ($user->hasRole(PermissionType::ROLE_SUPER_ADMIN)) {
    //             $permissions['is_super_admin'] = true;
    //         }

    //     }
    //     if ($user->type == UserType::AUTHOR) {
    //         $permissions['is_find_work_allowed'] = true;
    //     }

    //     return $permissions;
    // }

}
