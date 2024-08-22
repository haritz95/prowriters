<?php

namespace App\Http\Controllers\Admin\Business;

use App\Http\Controllers\Controller;
use App\Models\Business\Service;
use App\Models\Business\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return inertia('Admin/Business/Subjects/Index', [
            'data'     => [
                'title' => __('Subjects'),
            ],
            'filters'  => $request->only('filters'),
            'subjects' => Subject::query()
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
        return inertia()->modal('Admin/Business/Subjects/Create', [
            'data' => [
                'title'     => __('Add subject'),
                'dropdowns' => [
                    'services' => Service::all(),
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
        Subject::create($request->validate([
            'name'       => 'required|string|max:192|unique:subjects,name',
            'percentage' => config('app.validation_rules.percentage'),
            'services'   => 'required|array',
        ]))->services()->attach($request->services);

        return redirect()->route($this->getRedirectRoute())->withSuccess(__('Successfully created'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Subject $subject)
    {
        $subject['services'] = $subject->services()->pluck('service_id');

        return inertia()->modal('Admin/Business/Subjects/Create', [
            'data'            => [
                'title'     => __('Edit Subject'),
                'dropdowns' => [
                    'services' => Service::all(),
                ],
            ],
            'existing_record' => $subject,
        ])->baseRoute($this->getRedirectRoute());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subject $subject)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:192|unique:subjects,name,' . $subject->id,
            'percentage' => config('app.validation_rules.percentage'),
            'services'   => 'required|array',
        ]);

        $subject->fill($data)->update();
        $subject->services()->sync($data['services']);
        return redirect()->route($this->getRedirectRoute())->withSuccess(__('Successfully updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subject $subject)
    {
        $redirect = redirect()->route($this->getRedirectRoute());
        try {
            $subject->delete();
            $redirect->withSuccess(__('Successfully deleted'));
        } catch (\Illuminate\Database\QueryException$e) {
            $redirect->withFail(__('You cannot delete the service as it is associated with one or multiple orders'));
        } catch (\Exception$e) {
            $redirect->withFail(__('Could not perform the requested action'));
        }

        return $redirect;
    }

    private function getRedirectRoute()
    {
        return 'admin.subjects.index';
    }
}
