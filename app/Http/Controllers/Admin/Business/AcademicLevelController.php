<?php

namespace App\Http\Controllers\Admin\Business;

use App\Http\Controllers\Controller;
use App\Models\Business\AcademicLevel;
use App\Models\Business\Service;
use Illuminate\Http\Request;

class AcademicLevelController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Service $service)
    {
        return inertia('Admin/Business/AcademicLevels/Index', [
            'data'            => [
                'title'   => __('Academic Levels'),
                'service' => $service,
            ],
            'filters'         => $request->only('filters'),
            'academic_levels' => AcademicLevel::query()
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
    public function create(Service $service)
    {
        return inertia()->modal('Admin/Business/AcademicLevels/Create', [
            'data' => [
                'title'                => __('Add Academic Level'),               
                'urls'                 => [
                    'submit_form' => route('admin.academicLevels.store', $service->slug),
                ],
                'url_query_parameters' => $this->getQueryParameterPreviousUrl(),
            ],
        ])->baseRoute('admin.academicLevels.index', $service->slug);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Service $service)
    {
        AcademicLevel::create($request->validate([
            'name'       => 'required|string|max:192|unique:academic_levels,name',
            'percentage' => config('app.validation_rules.percentage'),
        ]));

        return redirect()->route('admin.academicLevels.index', ['service' => $service->slug])->withSuccess(__('Successfully created'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Service $service, AcademicLevel $academicLevel)
    {
        return inertia()->modal('Admin/Business/AcademicLevels/Create', [
            'data'            => [
                'title' => __('Edit Academic Level'),
                'urls'  => [
                    'submit_form' => route('admin.academicLevels.update', ['service' => $service->slug, 'academicLevel' => $academicLevel->slug]),
                ],
            ],
            'existing_record' => $academicLevel,
        ])->baseRoute('admin.academicLevels.index', ['service' => $service->slug]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Service $service, AcademicLevel $academicLevel)
    {
        $academicLevel->fill($request->validate([
            'name'       => 'required|string|max:192|unique:academic_levels,name,' . $academicLevel->id,
            'percentage' => config('app.validation_rules.percentage'),
        ]))->update();

        return redirect()->route('admin.academicLevels.index', ['service' => $service->slug])->withSuccess(__('Successfully updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Service $service, AcademicLevel $academicLevel)
    {
        $redirect = redirect()->route('admin.academicLevels.index', ['service' => $service->slug]);
        try {
            $academicLevel->delete();
            $redirect->withSuccess(__('Successfully deleted'));
        } catch (\Illuminate\Database\QueryException $e) {
            $redirect->withFail(__('You cannot delete the service as it is associated with one or multiple orders'));
        } catch (\Exception $e) {
            $redirect->withFail(__('Could not perform the requested action'));
        }

        return $redirect;
    }

}
