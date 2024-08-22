<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ChangeProfilePhotoRequest;
use App\Http\Requests\StoreAuthorRequest;
use App\Models\Author\AuthorProfile;
use App\Models\Author\EducationLevel;
use App\Models\Billing\Bill;
use App\Models\Business\AuthorLevel;
use App\Models\Business\Service;
use App\Models\Business\Subject;
use App\Models\Country;
use App\Models\ProjectManagement\Task;
use App\Models\User;
use App\Services\AvatarUploadService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;

class AuthorController extends Controller
{

    public function index(Request $request)
    {
        return inertia('Admin/Authors/Index', [
            'data'    => function () {
                // ALWAYS included on first visit
                // OPTIONALLY included on partial reloads
                // ONLY evaluated when needed
                return [
                    'title'     => __('Authors'),
                    'dropdowns' => [
                        'services'         => Service::active()->get(),
                        'subjects'         => Subject::all(),
                        'education_levels' => EducationLevel::all(),
                        'author_levels'    => AuthorLevel::all(),
                    ],
                    'urls'      => [
                        'new_item' => route('admin.authors.create'),
                        'search'   => route('admin.authors.index'),
                    ],
                ];
            },
            'filters' => $request->only('filters'),
            'authors' => User::authors(true)
                ->select(['users.uuid', 'code', 'first_name', 'last_name', 'email', 'phone', 'photo', 'last_login_at', 'inactive', 'author_levels.name AS author_level_name', 'education_levels.name AS education_level_name'])
                ->join('author_profiles', 'author_profiles.user_id', '=', 'users.id')
                ->join('education_levels', 'education_levels.id', '=', 'author_profiles.education_level_id')
                ->join('author_levels', 'author_levels.id', '=', 'author_profiles.author_level_id')
                ->when($request->filled('filters.name'), function ($q) use ($request) {
                    return $q->where(function ($subQuery) use ($request) {
                        return $subQuery->where('first_name', 'like', '%' . $request->filters['name'] . '%')
                            ->orWhere('last_name', 'like', '%' . $request->filters['name'] . '%');
                    });
                })
                ->when($request->filled('filters.code'), function ($q) use ($request) {
                    return $q->where(function ($subQuery) use ($request) {
                        return $subQuery->where('code', 'like', '%' . $request->filters['code'] . '%');
                    });
                })
                ->when($request->filled('filters.email'), function ($q) use ($request) {
                    return $q->where(function ($subQuery) use ($request) {
                        return $subQuery->where('email', 'like', '%' . $request->filters['email'] . '%');
                    });
                })
                ->when($request->filled('filters.education_level_id'), function ($q) use ($request) {
                    return $q->where(function ($subQuery) use ($request) {
                        return $subQuery
                            ->where('education_level_id', $request->filters['education_level_id']);
                    });
                })
                ->when($request->filled('filters.author_level_id'), function ($q) use ($request) {
                    return $q->where(function ($subQuery) use ($request) {
                        return $subQuery
                            ->where('author_level_id', $request->filters['author_level_id']);
                    });
                })
                ->when($request->filled('filters.service_id'), function ($q) use ($request) {
                    return $q->where(function ($subQuery) use ($request) {
                        return $subQuery
                            ->where('service_id_1', $request->filters['service_id'])
                            ->orWhere('service_id_2', $request->filters['service_id'])
                            ->orWhere('service_id_3', $request->filters['service_id']);
                    });
                })
                ->when($request->filled('filters.subject_id'), function ($q) use ($request) {
                    return $q->where(function ($subQuery) use ($request) {
                        return $subQuery
                            ->where('subject_id_1', $request->filters['subject_id'])
                            ->orWhere('subject_id_2', $request->filters['subject_id'])
                            ->orWhere('subject_id_3', $request->filters['subject_id'])
                            ->orWhere('subject_id_4', $request->filters['subject_id'])
                            ->orWhere('subject_id_5', $request->filters['subject_id']);
                    });
                })

                ->when(!(is_boolean_true($request->input('filters.inactive'))), function ($q) {
                    return $q->active();
                })
                ->orderBy('users.id', 'ASC')
                ->paginate(config('app.pagination.per_page'))
                ->withQueryString(),
        ]);
    }

    public function create()
    {
        return inertia()->modal('Admin/Authors/Create', [
            'data' => [
                'title'     => __('Add author'),
                'urls'      => [
                    'submit_form' => route('admin.authors.store'),
                ],
                'dropdowns' => [
                    'countries'        => Country::all(),
                    'timezones'        => User::getTimeZones(),
                    'education_levels' => EducationLevel::all(),
                    'subjects'         => Subject::all(),
                    'services'         => Service::all(),
                    'author_levels'    => AuthorLevel::all(),
                ],
            ],
        ])->baseRoute('admin.authors.index');
    }

    public function store(StoreAuthorRequest $request, UserService $userService)
    {
        $user = $userService->createAuthor($request->validated());
        return redirect()->route('admin.authors.show', $user->uuid)->withSuccess(__('Successfully created'));
    }

    public function show(User $user)
    {
        $user->load(['country', 'attachments']);

        $user->profile = AuthorProfile::with(['educationLevel', 'authorLevel'])
            ->where('user_id', $user->id)->get()->first();

        $attachment = $user->attachments->first();

        return inertia('Admin/Authors/Show', [
            'data'   => [
                'title'           => __('Author Profile'),
                'services'        => Service::select(['id', 'name'])->whereIn('id', array_filter([
                    $user->profile->service_id_1,
                    $user->profile->service_id_2,
                    $user->profile->service_id_3,
                ]))->get(),
                'subjects'        => Subject::whereIn('id', array_filter([
                    $user->profile->subject_id_1,
                    $user->profile->subject_id_2,
                    $user->profile->subject_id_3,
                    $user->profile->subject_id_4,
                    $user->profile->subject_id_5,
                ]))->get(),
                'attachment_uuid' => ($attachment) ? $attachment->uuid : null,
            ],
            'author' => $user,
        ]);
    }

    public function edit(User $user)
    {
        $author = $user->only([
            'id',
            'uuid',
            'photo',
            'small_avatar',
            'first_name',
            'last_name',
            'email',
            'phone',
            'country_code',
            'timezone',
            'language',
            'inactive',
        ]);
        $author['profile'] = AuthorProfile::where('user_id', $user->id)->get()->first();

        return inertia('Admin/Authors/Edit', [
            'data'   => [
                'title'     => __('Edit Profile'),
                'urls'      => [
                    'submit_form' => route('admin.authors.update', $user->uuid),
                ],
                'dropdowns' => [
                    'countries'        => Country::all(),
                    'timezones'        => User::getTimeZones(),
                    'education_levels' => EducationLevel::all(),
                    'subjects'         => Subject::all(),
                    'services'         => Service::all(),
                    'author_levels'    => AuthorLevel::all(),
                ],
            ],
            'author' => $author,
        ]);
    }

    public function update(StoreAuthorRequest $request, User $user, AuthorProfile $userProfile)
    {
        $data = $request->validated();

        User::find($user->id)->fill($data)->update();

        foreach ($data as $key => $value) {
            if (in_array($key, $userProfile->getFillable())) {
                $profile_data[$key] = $value;
            }
        }

        AuthorProfile::where('user_id', $user->id)->update($profile_data);

        return redirect()->route('admin.authors.show', $user->uuid)->withSuccess(__('Successfully updated'));
    }

    public function destroy(User $user)
    {
        $redirect = redirect()->route('admin.authors.index');

        DB::beginTransaction();

        try {

            AuthorProfile::where('user_id', $user->id)->delete();
            $user->delete();
            $redirect->withSuccess(__('Successfully deleted'));
            DB::commit();

        } catch (\Illuminate\Database\QueryException $e) {
            $redirect->withFail(__('You cannot delete the user as he/she is associated with one or multiple entities'));
            DB::rollback();
        } catch (\Exception $e) {
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
                    'submit_form' => route('admin.authors.password.update', $user->uuid),
                ],
            ],
        ])->baseRoute('admin.authors.show', $user->uuid);
    }

    public function updatePassword(ChangePasswordRequest $request, User $user, UserService $userService)
    {
        $userService->changePassword($user, $request->password);

        return redirect()->route('admin.authors.show', $user->uuid)->withSuccess(__('Successfully updated'));
    }

    public function avatar(User $user)
    {
        return inertia()->modal('Admin/Shared/ChangeAvatar', [
            'data' => [
                'title' => __('Change Avatar'),
                'urls'  => [
                    'submit_form' => route('admin.authors.avatar.update', $user->uuid),
                ],
            ],
        ])->baseRoute('admin.authors.show', $user->uuid);
    }

    public function updateAvatar(ChangeProfilePhotoRequest $request, User $user, AvatarUploadService $avatar)
    {
        if ($avatar->upload($request, $user)) {
            return redirect()->route('admin.authors.show', $user->uuid)->withSuccess(__('Successfully updated'));
        } else {
            return redirect()->back()->withError(__('Avatar could not be updated'));
        }
    }

    public function tasks(Request $request, User $user)
    {
        return inertia('Admin/Authors/Tasks', [
            'data'    => [
                'title' => __('Tasks'),
            ],
            'author'  => $user,
            'filters' => $request->only('filters'),
            'tasks'   => Task::with(['status', 'service'])
                ->where('author_id', $user->id)
                ->orderBy('id', 'DESC')
                ->paginate(config('app.pagination.per_page'))
                ->withQueryString(),
        ]);
    }

    public function bills(Request $request, User $user)
    {
        return inertia('Admin/Authors/Bills', [
            'data'    => [
                'title' => __('Bills'),
            ],
            'author'  => $user,
            'filters' => $request->only('filters'),
            'bills'   => Bill::where('author_id', $user->id)
                ->orderBy('id', 'DESC')
                ->paginate(config('app.pagination.per_page'))
                ->withQueryString(),
        ]);
    }

    public function search(Request $request)
    {
        if (isset($request->q) && $request->q) {
            $data = User::select(['id', 'first_name', 'last_name', 'email'])
                ->authors()
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
