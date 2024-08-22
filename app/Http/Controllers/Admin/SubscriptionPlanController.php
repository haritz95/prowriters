<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubscriptionPlanRequest;
use App\Models\Billing\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubscriptionPlanController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return inertia('Admin/Settings/SubscriptionPlans/Index', [
            'data'               => [
                'title' => __('Subscription Plans'),
            ],
            'filters'            => $request->only('filters'),
            'subscription_plans' => SubscriptionPlan::query()
                ->when($request->filled('filters.search'), function ($q) use ($request) {
                    return $q->where('title', 'like', '%' . $request->filters['search'] . '%');
                })
                ->orderBy('price', 'ASC')
                ->paginate(config('app.pagination.per_page'))
                ->withQueryString(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return inertia()->modal('Admin/Settings/SubscriptionPlans/Create', [
            'data' => [
                'title' => __('Add Subscription Plan'),
            ],
        ])->baseRoute($this->getRedirectRoute());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSubscriptionPlanRequest $request)
    {

        $data                 = $request->validated();
        $data['service_type'] = 'ai_content';
        $data['uuid']         = Str::orderedUuid();
        SubscriptionPlan::create($data);

        return redirect()->route($this->getRedirectRoute())->withSuccess(__('Successfully created'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(SubscriptionPlan $subscriptionPlan)
    {
        return inertia()->modal('Admin/Settings/SubscriptionPlans/Create', [
            'data'            => [
                'title' => __('Edit Subscription Plan'),
            ],
            'existing_record' => $subscriptionPlan,
        ])->baseRoute($this->getRedirectRoute());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SubscriptionPlan $subscriptionPlan)
    {
        $data = $request->validate([
            'is_free'                                => 'nullable',
            'stripe_id'                              => 'required_without:is_free|nullable|unique:subscription_plans,stripe_id,' . $subscriptionPlan->id,
            'title'                                  => 'required|max:192|unique:subscription_plans,title,' . $subscriptionPlan->id,
            'description'                            => 'nullable|max:2000',
            'price'                                  => 'nullable|numeric|min:0',
            'number_of_characters_allowed_per_month' => 'required|integer',

        ]);

        $subscriptionPlan->fill($data)->update();

        return redirect()->route($this->getRedirectRoute())->withSuccess(__('Successfully updated'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(SubscriptionPlan $subscriptionPlan)
    {
        $redirect = redirect()->route($this->getRedirectRoute());
        try {
            $subscriptionPlan->delete();
            $redirect->withSuccess(__('Successfully deleted'));
        } catch (\Illuminate\Database\QueryException$e) {
            $redirect->withFail(__('You cannot delete the plan as it is associated with one or multiple records'));
        } catch (\Exception$e) {
            $redirect->withFail(__('Could not perform the requested action'));
        }

        return $redirect;
    }

    private function getRedirectRoute()
    {
        return 'admin.settings.subscriptionPlans.index';
    }
}
