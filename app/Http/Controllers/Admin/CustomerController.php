<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ChangeProfilePhotoRequest;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\StoreWalletAdjustmentRequest;
use App\Models\Country;
use App\Models\CustomerProfile;
use App\Models\NumberGenerator;
use App\Models\Payments\Payment;
use App\Models\ProjectManagement\Task;
use App\Models\User;
use App\Models\Wallets\WalletAdjustment;
use App\Models\Wallets\WalletTransaction;
use App\Services\AvatarUploadService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CustomerController extends Controller
{

    public function index(Request $request)
    {
        return inertia('Admin/Customers/Index', [
            'data'      => function () {
                // ALWAYS included on first visit
                // OPTIONALLY included on partial reloads
                // ONLY evaluated when needed
                return [
                    'title' => __('Customers'),
                ];
            },
            'filters'   => $request->only('filters'),
            'customers' => User::customers(true)->select('uuid', 'first_name', 'last_name', 'email', 'photo', 'phone', 'last_login_at', 'inactive')
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
        return inertia()->modal('Admin/Customers/Create', [
            'data' => [
                'title'     => __('Add customer'),
                'urls'      => [
                    'submit_form' => route('admin.customers.store'),
                ],
                'dropdowns' => [
                    'countries' => Country::all(),
                    'timezones' => User::getTimeZones(),
                ],
            ],
        ])->baseRoute('admin.customers.index');
    }

    public function store(StoreCustomerRequest $request, UserService $userService)
    {
        $user = $userService->createCustomer($request->validated());

        return redirect()->route('admin.customers.show', $user->uuid)->withSuccess(__('Successfully created'));
    }

    public function show(User $user)
    {
        $user->load(['country', 'customerProfile'])->loadCount('customerTasks');

        $user->wallet_balance = $user->wallet()->balance();
        return inertia('Admin/Customers/Show', [
            'data'     => [
                'title' => __('Customer Profile'),
            ],
            'customer' => $user,
        ]);
    }

    public function edit(User $user, CustomerProfile $profile)
    {
        // Get the fields for the customer
        $rules = (new StoreCustomerRequest())->rules();

        if (isset($rules['password'])) {
            unset($rules['password']);
        }

        $fields       = array_keys($rules);
        $user_records = $user->only($fields);

        // Load customer profile
        $user->load('customerProfile');
        if ($user->customerProfile) {
            $profile_fields = $user->customerProfile->toArray();
        } else {
            $profile_fields = [];
        }

        foreach ($profile_fields as $field => $value) {
            if (in_array($field, $profile->fillable)) {
                $user_records[$field] = $value;
            }
        }

        return inertia()->modal('Admin/Customers/Create', [
            'data'            => [
                'title'     => __('Edit') . ' ' . __('Customer Profile'),
                'urls'      => [
                    'submit_form' => route('admin.customers.update', $user->uuid),
                ],
                'dropdowns' => [
                    'countries' => Country::all(),
                    'timezones' => User::getTimeZones(),
                ],
            ],
            'existing_record' => $user_records,
        ])->baseRoute('admin.customers.show', $user->uuid);
    }

    public function update(StoreCustomerRequest $request, User $user, UserService $userService)
    {
        $userService->updateCustomer($user, $request);

        return redirect()->route('admin.customers.show', $user->uuid)->withSuccess(__('Successfully updated'));
    }

    public function destroy(Request $request, User $user)
    {

        if ($user->wallet()->balance() > 0) {
            return redirect()->route('admin.customers.show', $user->uuid)->withFail(__('You cannot delete the user as his/her wallet has more than zero balance'));
        }

        DB::beginTransaction();

        try {

            DB::table('wallets')->where('user_id', $user->id)->delete();
            $user->delete();

            $redirect = redirect()->route('admin.customers.index')->withSuccess(__('Successfully deleted'));
            DB::commit();
        } catch (\Illuminate\Database\QueryException$e) {
            $redirect = redirect()->route('admin.customers.show', $user->uuid)->withFail(__('You cannot delete the user as he/she is associated with one or multiple records'));
            DB::rollback();
        } catch (\Exception$e) {
            $redirect = redirect()->route('admin.customers.show', $user->uuid)->withFail(__('Could not perform the requested action'));
            DB::rollback();
        }

        return $redirect;
    }

    public function tasks(User $user)
    {
        return inertia('Admin/Customers/Tasks', [
            'data'     => [
                'title' => __('Tasks'),
            ],
            'customer' => $user,
            'tasks'    => Task::select(['id', 'uuid', 'number', 'task_status_id', 'service_id', 'total'])->with(['status', 'service'])
                ->where('customer_id', $user->id)
                ->whereNotNull('task_status_id')
                ->orderBy('id', 'DESC')
                ->paginate(config('app.pagination.per_page'))
                ->withQueryString(),
        ]);
    }

    public function payments(User $user)
    {
        return inertia('Admin/Customers/Payments', [
            'data'     => [
                'title' => __('Payments'),
            ],
            'customer' => $user,
            'payments' => Payment::where('customer_id', $user->id)
                ->orderBy('id', 'DESC')
                ->paginate(config('app.pagination.per_page'))
                ->withQueryString(),

        ]);
    }

    public function walletTransactions(Request $request, User $user)
    {
        return inertia('Admin/Customers/WalletTransactions', [
            'data'         => [
                'title' => __('Wallet Transactions'),
            ],
            'customer'     => $user,
            'transactions' => WalletTransaction::where('wallet_id', $user->wallet()->id())
                ->orderBy('id', 'DESC')
                ->paginate(config('app.pagination.per_page'))
                ->onEachSide(2)
                ->withQueryString()
                ->through(fn($transaction) => [
                    'date'                 => $transaction->created_at,
                    'number'               => $transaction->number,
                    'type'                 => $transaction->type,
                    'transactionable_type' => WalletTransaction::translateJargon($transaction->transactionable_type),
                    'description'          => $transaction->relatedTable->number,
                    'reference'            => optional($transaction->relatedTable)->number,
                    'reference_link'       => WalletTransaction::getReferenceLinkForAdmin($transaction),
                    'amount'               => format_currency($transaction->amount),

                ]),
        ]);
    }

    public function password(User $user)
    {
        return inertia()->modal('Admin/Shared/ChangePassword', [
            'data' => [
                'title' => __('Change Password'),
                'urls'  => [
                    'submit_form' => route('admin.customers.password.update', $user->uuid),
                ],
            ],
        ])->baseRoute('admin.customers.show', $user->uuid);
    }

    public function updatePassword(ChangePasswordRequest $request, User $user, UserService $userService)
    {
        $userService->changePassword($user, $request->password);

        return redirect()->route('admin.customers.show', $user->uuid)->withSuccess(__('Successfully updated'));
    }

    public function avatar(User $user)
    {
        return inertia()->modal('Admin/Shared/ChangeAvatar', [
            'data' => [
                'title' => __('Change Avatar'),
                'urls'  => [
                    'submit_form' => route('admin.customers.avatar.update', $user->uuid),
                ],
            ],
        ])->baseRoute('admin.customers.show', $user->uuid);
    }

    public function updateAvatar(ChangeProfilePhotoRequest $request, User $user, AvatarUploadService $avatar)
    {
        if ($avatar->upload($request, $user)) {
            return redirect()->route('admin.customers.show', $user->uuid)->withSuccess(__('Successfully updated'));
        } else {
            return redirect()->back()->withError(__('Avatar could not be updated'));
        }
    }

    public function adjustWallet(User $user)
    {
        return inertia()->modal('Admin/Customers/AdjustWallet', [
            'data' => [
                'title'     => __('Adjust Wallet'),
                'urls'      => [
                    'submit_form' => route('admin.customers.wallets.adjust.store', $user->uuid),
                ],
                'dropdowns' => [
                    'adjustment_types' => [
                        ['id' => 'add', 'name' => __('Add to balance')],
                        ['id' => 'deduct', 'name' => __('Deduct from balance')],
                    ],

                ],
            ],
        ])->baseRoute('admin.customers.show', $user->uuid);
    }

    public function storeAdjustWallet(StoreWalletAdjustmentRequest $request, User $user)
    {
        $request['uuid']        = Str::orderedUuid();
        $request['number']      = NumberGenerator::gen(WalletAdjustment::class);
        $request['user_id']     = $user->id;
        $request['adjuster_id'] = auth()->user()->id;
        $walletAdjustment       = WalletAdjustment::create($request->all());

        if ($walletAdjustment->type == 'add') {
            $user->wallet()->deposit($walletAdjustment->amount, $walletAdjustment);
        } else {
            $user->wallet()->pay($walletAdjustment->amount, $walletAdjustment);
        }
        return redirect()->route('admin.customers.show', $user->uuid)->withSuccess(__('Successfully created'));
    }

    public function search(Request $request)
    {
        if (isset($request->q) && $request->q) {
            $data = User::select(['id', 'first_name', 'last_name', 'email'])
                ->customers()
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
