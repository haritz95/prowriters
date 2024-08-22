<?php

namespace App\Http\Controllers\Admin\Business;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePackageRequest;
use App\Models\Business\Assignment;
use App\Models\Business\AuthorLevel;
use App\Models\Business\Service;
use App\Models\Business\Urgency;
use Illuminate\Http\Request;

class PackageController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Service $service)
    {
        return inertia('Admin/Business/Packages/Index', [
            'data'        => function () use ($service) {
                // ALWAYS included on first visit
                // OPTIONALLY included on partial reloads
                // ONLY evaluated when needed
                return [
                    'title'   => __($service->name) .' > ' .__($service->assignment_label),
                    'service' => $service,
                ];
            },
            'filters'     => $request->only('filters'),
            'assignments' => Assignment::whereHas('service', function ($q) use ($service) {
                $q->select(['id', 'name'])->where('id', $service->id);

            })
                ->when($request->filled('filters.search'), function ($q) use ($request) {
                    return $q->where('name', 'like', '%' . $request->filters['search'] . '%');
                })

                ->orderBy('name', 'ASC')
                ->paginate(config('app.pagination.per_page'))
                ->withQueryString(),
        ]);
    }

    public function create(Service $service)
    {
        return inertia()->modal('Admin/Business/Packages/Create', [
            'data' => [
                'title'                => __('Add') .' ' . $service->assignment_label,
                'urls'                 => [
                    'submit_form' => route('admin.packages.store', $service->slug),
                ],
                'url_query_parameters' => $this->getQueryParameterPreviousUrl(),
                'dropdowns'            => [
                    'urgencies' => Urgency::all(),
                    'author_levels' => AuthorLevel::all(),
                ],
            ],
        ])->baseRoute('admin.packages.index', $service->slug);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePackageRequest $request, Service $service)
    {
        $service->assignments()->create($request->validated());
        return redirect()->route('admin.packages.index', [$service->slug, $request->url_query_parameters])->withSuccess(__('Successfully created'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Service $service, Assignment $package)
    {
        return inertia()->modal('Admin/Business/Packages/Create', [
            'data'            => [
                'title'                => __('Edit') .' ' . $service->assignment_label,
                'urls'                 => [
                    'submit_form' => route('admin.packages.update', ['service' => $service->slug, 'package' => $package->slug]),
                ],
                'url_query_parameters' => $this->getQueryParameterPreviousUrl(),
                'dropdowns'            => [
                    'urgencies' => Urgency::all(),
                    'author_levels' => AuthorLevel::all(),
                ],
            ],
            'existing_record' => $package,
        ])->baseRoute('admin.packages.index', [$service->slug, $this->getQueryParameterPreviousUrl()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(StorePackageRequest $request, Service $service, Assignment $package)
    {
        $package->fill($request->validated())->update();

        return redirect()->route('admin.packages.index', [$service->slug, $request->url_query_parameters])->withSuccess(__('Successfully updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Service $service, Assignment $package)
    {
        $redirect = redirect()->route('admin.packages.index', [$service->slug, $this->getQueryParameterPreviousUrl()]);
        try {
            $package->delete();
            $redirect->withSuccess(__('Successfully deleted'));
        } catch (\Illuminate\Database\QueryException $e) {
            $redirect->withFail(__('You cannot delete the item as it is associated with one or multiple orders'));
        } catch (\Exception $e) {
            $redirect->withFail(__('Could not perform the requested action'));
        }

        return $redirect;
    }

    
}
