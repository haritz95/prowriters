<?php

namespace App\Http\Controllers\Admin\Business;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAssignmentRequest;
use App\Models\Business\Assignment;
use App\Models\Business\Service;
use App\Models\Business\Urgency;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Service $service)
    {
        return inertia('Admin/Business/Assignments/Index', [
            'data'        => function () use ($service) {
                // ALWAYS included on first visit
                // OPTIONALLY included on partial reloads
                // ONLY evaluated when needed
                return [
                    'title'   => __($service->assignment_label),
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
        return inertia()->modal('Admin/Business/Assignments/Create', [
            'data' => [
                'title'                => __('Add') . ' ' . __($service->assignment_label),
                'urls'                 => [
                    'submit_form' => route('admin.assignments.store', $service->slug),
                ],
                'url_query_parameters' => $this->getQueryParameterPreviousUrl(),
                'dropdowns'            => [
                    'urgencies' => Urgency::all(),
                ],
            ],
        ])->baseRoute('admin.assignments.index', $service->slug);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAssignmentRequest $request, Service $service)
    {
        $service->assignments()->create($request->validated());
        return redirect()->route('admin.assignments.index', [$service->slug, $request->url_query_parameters])->withSuccess(__('Successfully created'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Service $service, Assignment $assignment)
    {
        return inertia()->modal('Admin/Business/Assignments/Create', [
            'data'            => [
                'title'                => __('Edit') . ' ' . __($service->assignment_label),
                'urls'                 => [
                    'submit_form' => route('admin.assignments.update', ['service' => $service->slug, 'assignment' => $assignment->slug]),
                ],
                'url_query_parameters' => $this->getQueryParameterPreviousUrl(),
                'dropdowns'            => [
                    'urgencies' => Urgency::all(),
                ],
            ],
            'existing_record' => $assignment,
        ])->baseRoute('admin.assignments.index', [$service->slug, $this->getQueryParameterPreviousUrl()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreAssignmentRequest $request, Service $service, Assignment $assignment)
    {
        $assignment->fill($request->validated())->update();

        return redirect()->route('admin.assignments.index', [$service->slug, $request->url_query_parameters])->withSuccess(__('Successfully updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Service $service, Assignment $assignment)
    {
        $redirect = redirect()->route('admin.assignments.index', [$service->slug, $this->getQueryParameterPreviousUrl()]);
        try {
            $assignment->delete();
            $redirect->withSuccess(__('Successfully deleted'));
        } catch (\Illuminate\Database\QueryException $e) {
            $redirect->withFail(__('You cannot delete the item as it is associated with one or multiple orders'));
        } catch (\Exception $e) {
            $redirect->withFail(__('Could not perform the requested action'));
        }

        return $redirect;
    }

    
}
