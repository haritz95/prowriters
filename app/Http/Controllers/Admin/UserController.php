<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Enums\UserType;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Enums\PermissionType;
use App\Services\UserService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\AvatarUploadService;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Services\ManageUserPermissionService;
use App\Http\Requests\ChangeProfilePhotoRequest;

class UserController extends Controller
{

    public function index(Request $request)
    {
        return inertia('Admin/Users/Index', [
            'data'    => function () {
                // ALWAYS included on first visit
                // OPTIONALLY included on partial reloads
                // ONLY evaluated when needed
                return [
                    'title' => __('Users'),
                    'roles' => PermissionType::getRolesAsList(),                    
                ];
            },
            'filters' => $request->only('filters'),
            'users'   => User::admins(true)->with(['roles' => function ($q) {
                $q->select('name');
            }])
                ->select('id', 'uuid', 'first_name', 'last_name', 'email', 'photo', 'last_login_at', 'inactive')
                ->when($request->filled('filters.name'), function ($q) use ($request) {
                    return $q->where(function ($subQuery) use ($request) {
                        return $subQuery->where('first_name', 'like', '%' . $request->filters['name'] . '%')
                            ->orWhere('last_name', 'like', '%' . $request->filters['name'] . '%');
                    });
                })
                ->when($request->filled('filters.email'), function ($q) use ($request) {
                    return $q->where(function ($subQuery) use ($request) {
                        return $subQuery->where('email', 'like', '%' . $request->filters['email'] . '%');
                    });
                })
                ->when(!(is_boolean_true($request->input('filters.inactive'))), function ($q) {                   
                    return $q->active();
                })
                ->paginate(config('app.pagination.per_page'))
                ->withQueryString(),
        ]);
    }

    public function create()
    {
        return inertia()->modal('Admin/Users/Create', [
            'data' => [
                'title'     => __('Add user'),
                'roles'     => PermissionType::getRolesDropdown(),
                'urls'      => [
                    'submit_form' => route('admin.users.store'),
                ],
                'dropdowns' => [
                    'countries' => Country::all(),
                    'timezones' => User::getTimeZones(),
                ],
            ],
        ])->baseRoute('admin.users.index');
    }

    public function store(StoreUserRequest $request, UserService $userService, ManageUserPermissionService $userPermissionService)
    {
        $user = $userService->createAdmin($request->validated());
        $userPermissionService->assignRole($request->input('role'), $user);
        return redirect()->route('admin.users.show', $user->uuid)->withSuccess(__('Successfully created'));
    }

    public function show(User $user)
    {
        return inertia('Admin/Users/Show', [
            'data' => [
                'title'     => __('User Profile'),
                'role_name' => PermissionType::getRoleNameById($user->getRoleNames()->first()),
            ],
            'user' => $user,
        ]);
    }

    public function edit(User $user)
    {
        return inertia()->modal('Admin/Users/Edit', [
            'data' => [
                'title'     => __('Edit') . ' ' . __('Profile'),
                'roles'     => PermissionType::getRolesDropdown(),
                'urls'      => [
                    'submit_form' => route('admin.users.update', $user->uuid),
                ],
                'dropdowns' => [
                    'countries' => Country::all(),
                    'timezones' => User::getTimeZones(),
                ],
            ],
            'user' => $user,
        ])->baseRoute('admin.users.show', $user->uuid);
    }

    public function update(StoreUserRequest $request, User $user)
    {
        $user->fill($request->validated())->update();
        //$userPermissionService->assignRole($request->input('role'), $user);
        return redirect()->route('admin.users.show', $user->uuid)->withSuccess(__('Successfully updated'));
    }

    public function destroy(Request $request, User $user)
    {
        $redirect = redirect()->route('admin.users.index');

        DB::beginTransaction();

        try {

            $user->roles()->detach();
            $user->delete();

            $redirect->withSuccess(__('Successfully deleted'));
            DB::commit();
        } catch (\Illuminate\Database\QueryException$e) {
            $redirect->withFail(__('You cannot delete the user as he/she is associated with one or multiple entities'));
            DB::rollback();
        } catch (\Exception$e) {
            $redirect->withFail(__('Could not perform the requested action'));
            DB::rollback();
        }

        return $redirect;
    }

    public function password(User $user)
    {
        return inertia()->modal('Admin/Shared/ChangePassword', [
            'data' => [
                'title' => __('Change Password'),
                'urls'  => [
                    'submit_form' => route('admin.users.password.update', $user->uuid),
                ],
            ],
        ])->baseRoute('admin.users.show', $user->uuid);
    }

    public function updatePassword(ChangePasswordRequest $request, User $user, UserService $userService)
    {
        $userService->changePassword($user, $request->password);

        return redirect()->route('admin.users.show', $user->uuid)->withSuccess(__('Successfully updated'));
    }

    public function avatar(User $user)
    {
        return inertia()->modal('Admin/Shared/ChangeAvatar', [
            'data' => [
                'title' => __('Change Avatar'),
                'urls'  => [
                    'submit_form' => route('admin.users.avatar.update', $user->uuid),
                ],
            ],
        ])->baseRoute('admin.users.show', $user->uuid);
    }

    public function updateAvatar(ChangeProfilePhotoRequest $request, User $user, AvatarUploadService $avatar)
    {
        if ($avatar->upload($request, $user)) {
            return redirect()->route('admin.users.show', $user->uuid)->withSuccess(__('Successfully updated'));
        } else {
            return redirect()->back()->withError(__('Avatar could not be updated'));
        }
    }

    public function permission(User $user)
    {
        return inertia()->modal('Admin/Users/Permission', [
            'data' => [
                'title'             => __('Edit') . ' ' . __('Permission'),
                'roles'             => PermissionType::getRolesDropdown(),
                'user_current_role' => $user->getRoleNames()->first(),
                'urls'              => [
                    'submit_form' => route('admin.users.permission.update', $user->uuid),
                ],
            ],
        ])->baseRoute('admin.users.show', $user->uuid);
    }

    public function updatePermission(Request $request, User $user, ManageUserPermissionService $userPermissionService)
    {
        $userPermissionService->assignRole($request->input('role'), $user);

        return redirect()->route('admin.users.show', $user->uuid)->withSuccess(__('Successfully updated'));
    }
    
    public function searchAll(Request $request)
    {
        if (isset($request->q) && $request->q) {
            $data = User::select(['id', 'first_name', 'last_name', 'email'])
                ->where('id', '<>', auth()->user()->id)
                ->where('type', '<>', UserType::CUSTOMER)
                ->where(function ($subQuery) use ($request) {
                    return $subQuery
                        ->where('first_name', 'like', '%' . $request->q . '%')
                        ->orWhere('last_name', 'like', '%' . $request->q . '%')
                        ->orWhere('email', 'like', '%' . $request->q . '%');
                })->get();
            return response()->json(['items' => $data, 'total_count' => $data->count()]);
        }
    }
}
