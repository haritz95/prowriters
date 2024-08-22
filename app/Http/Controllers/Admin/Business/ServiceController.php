<?php

namespace App\Http\Controllers\Admin\Business;

use App\Enums\ServiceType;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreServiceRequest;
use App\Models\Business\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function business(Request $request)
    {
        return inertia('Admin/Business/Index', [
            'data' => [
                'title' => __('Services & Pricing'),
                'links' => [
                    ['name' => __('Author Levels'), 'link' => route('admin.authorLevels.index'), 'icon' => 'fa-solid fa-cubes'],
                    ['name' => __('Urgencies'), 'link' => route('admin.urgencies.index'), 'icon' => 'fa-regular fa-clock'],
                    ['name' => __('Services'), 'link' => route('admin.services.index'), 'icon' => 'fa-solid fa-pen'],
                    ['name' => __('Subjects'), 'link' => route('admin.subjects.index'), 'icon' => 'fa-solid fa-book'],
                    ['name' => __('Additional Services'), 'link' => route('admin.additionalServices.index'), 'icon' => 'fa-solid fa-list-check'],

                ],

            ],
        ]);
    }

    public function configurationHome(Service $service)
    {
        return inertia('Admin/Business/Services/ConfigurationHome', [
            'data' => [
                'title'   => __('Business Setup'),
                'service' => $service,
                'links'   => Service::getBusinessMenuItemsByService($service),
            ],

        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return inertia('Admin/Business/Services/Index', [
            'data' => [
                'title'    => __('Services'),

            ],
            'filters'  => $request->only('filters'),
            'services' => Service::query()
                ->when($request->filled('filters.search'), function ($q) use ($request) {
                    return $q->where('name', 'like', '%' . $request->filters['search'] . '%');
                })
                ->paginate(config('app.pagination.per_page'))
                ->withQueryString(),
        ]);
    }

    public function create()
    {
        return inertia()->modal('Admin/Business/Services/Create', [
            'data' => [
                'title'     => __('Add service'),
                'dropdowns' => [
                    'service_types' => ServiceType::dropdownForFrontEnd(),
                ],
            ],
        ])->baseRoute('admin.services.index');
    }

    public function store(StoreServiceRequest $request)
    {
        Service::create($request->validated());
        return redirect()->route('admin.services.index')->withSuccess(__('Successfully created'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Service $service)
    {
        return inertia()->modal('Admin/Business/Services/Create', [
            'data'            => [
                'title' => __('Edit Service'),
            ],
            'existing_record' => $service,
        ])->baseRoute('admin.services.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreServiceRequest $request, Service $service)
    {
        $service->fill($request->validated())->update();

        return redirect()->route('admin.services.index')->withSuccess(__('Successfully updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Service $service)
    {
        $redirect = redirect()->route('admin.services.index');
        try {
            $service->delete();
            $redirect->withSuccess(__('Successfully deleted'));
        } catch (\Illuminate\Database\QueryException $e) {
            $redirect->withFail(__('You cannot delete the item as it is associated with one or multiple records'));
        } catch (\Exception $e) {
            $redirect->withFail(__('Could not perform the requested action'));
        }

        return $redirect;
    }

}
