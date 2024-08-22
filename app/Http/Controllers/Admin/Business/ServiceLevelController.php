<?php

namespace App\Http\Controllers\Admin\Business;

use App\Http\Controllers\Controller;
use App\Models\Business\ServiceLevel;
use Illuminate\Http\Request;

class ServiceLevelController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return inertia('Admin/Business/ServiceLevels/Index', [
            'data'           => [
                'title' => __('Customer Service Levels'),
            ],
            'filters'        => $request->only('filters'),
            'service_levels' => ServiceLevel::query()
                ->when($request->filled('filters.search'), function ($q) use ($request) {
                    return $q->where('name', 'like', '%' . $request->filters['search'] . '%');
                })
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
        return inertia()->modal('Admin/Business/ServiceLevels/Create', [
            'data' => [
                'title'     => __('Add Customer Service Level'),
                'dropdowns' => [
                    'price_types' => ServiceLevel::priceTypeAsDropdown(),
                ],
            ],
        ])->baseRoute($this->getRedirectRoute());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:192|unique:service_levels,name',            
            'price'       => 'required|numeric|min:0',
            'description' => 'nullable|string|max:1024',
            'is_default'  => 'nullable|boolean',
        ]);

        $is_default_count = ServiceLevel::where('is_default', true)->count();

        if ($is_default_count == 0) {
            $data['is_default'] = true;
        } else if ($is_default_count > 0 && $data['is_default'] == true) {
            ServiceLevel::where('is_default', true)->update(['is_default' => null]);
        }

        ServiceLevel::create($data);

        return redirect()->route($this->getRedirectRoute())->withSuccess(__('Successfully created'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(ServiceLevel $serviceLevel)
    {
        return inertia()->modal('Admin/Business/ServiceLevels/Create', [
            'data'            => [
                'title'              => __('Edit Customer Service Level'),          
            ],
            'existing_record' => $serviceLevel,
        ])->baseRoute($this->getRedirectRoute());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ServiceLevel $serviceLevel)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:192|unique:service_levels,name,' . $serviceLevel->id,            
            'price'       => 'required|numeric|min:0',
            'description' => 'nullable|string|max:1024',
            'is_default'  => 'nullable|boolean',
        ]);

        $defaultService = ServiceLevel::select('id')->where('is_default', true)->get()->first();

        if ($data['is_default'] != true && $defaultService && ($defaultService->id == $serviceLevel->id)) {
            // Throw validation error bag
            return redirect()->back()->withErrors(['is_default' => __('Default service level can not be changed')]);
        } else if ($data['is_default'] == true) {
            ServiceLevel::where('is_default', true)->update(['is_default' => null]);
        } else {
            $data['is_default'] = null;
        }

        $serviceLevel->fill($data)->update();

        return redirect()->route($this->getRedirectRoute())->withSuccess(__('Successfully updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ServiceLevel $serviceLevel)
    {
        $redirect = redirect()->route($this->getRedirectRoute());
        if ($serviceLevel->is_default == true) {
            $redirect->withFail(__('Cannot delete a default service level'));
        } else {
            try {
                $serviceLevel->delete();
                $redirect->withSuccess(__('Successfully deleted'));
            } catch (\Illuminate\Database\QueryException$e) {
                $redirect->withFail(__('You cannot delete the service as it is associated with one or multiple orders'));
            } catch (\Exception$e) {
                $redirect->withFail(__('Could not perform the requested action'));
            }
        }

        return $redirect;
    }

    private function getRedirectRoute()
    {
        return 'admin.serviceLevels.index';
    }
}
