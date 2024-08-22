<?php

namespace App\Http\Controllers\Admin\Business;

use App\Http\Controllers\Controller;
use App\Models\Business\Urgency;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UrgencyController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return inertia('Admin/Business/Urgencies/Index', [
            'data'      => [
                'title' => __('Urgencies'),
            ],
            'filters'   => $request->only('filters'),
            'urgencies' => Urgency::query()
                ->when($request->filled('filters.search'), function ($q) use ($request) {
                    return $q->where('name', 'like', '%' . $request->filters['search'] . '%');
                })
            // ->when(!(($request->input('filters.inactive') == 'true')), function ($q) use ($request) {
            //     return $q->active();
            // })
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
        return inertia()->modal('Admin/Business/Urgencies/Create', [
            'data' => [
                'title'     => __('Add urgency'),
                'dropdowns' => [
                    'urgency_types' => Urgency::types(),
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
        Urgency::create($request->validate([
            'name'                 => 'required|string|max:192|unique:urgencies',
            'type'                 => 'required|in:hours,days',
            'value'                => [
                'required',
                'integer',
                'min:1',
                Rule::unique('urgencies')->where(function ($query) use ($request) {
                    return $query->where('type', $request->type)
                        ->where('value', $request->value);
                }),
            ],
            'type_for_author'  => 'required|in:hours,days',
            'value_for_author' => 'required|integer',
            'percentage'           => config('app.validation_rules.percentage'),
        ], [
            'value.unique' => __('The urgency duration and type already exists'),
        ], [
            'value'                => __('Duration'),
            'value_for_author' => __('Duration For Authors'),
            'percentage'           => __('Percentage'),
        ]));

        return redirect()->route($this->getRedirectRoute())->withSuccess(__('Successfully created'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Urgency $urgency)
    {
        return inertia()->modal('Admin/Business/Urgencies/Create', [
            'data'            => [
                'title'     => __('Edit urgency'),
                'dropdowns' => [
                    'urgency_types' => Urgency::types(),
                ],
            ],
            'existing_record' => $urgency,
        ])->baseRoute($this->getRedirectRoute());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Urgency $urgency)
    {

        $urgency->fill($request->validate([
            'name'                 => 'required|string|max:192|unique:urgencies,name,' . $urgency->id,
            'type'                 => 'required|in:hours,days',
            'value'                => [
                'required',
                'integer',
                'min:1',
                Rule::unique('urgencies')->where(function ($query) use ($request, $urgency) {
                    return $query->where('type', $request->type)
                        ->where('value', $request->value)
                        ->where('id', '<>', $urgency->id);
                }),
            ],
            'type_for_author'  => 'required|in:hours,days',
            'value_for_author' => 'required|integer',
            'percentage'           => config('app.validation_rules.percentage'),
        ], [
            'value.unique' => __('The urgency duration and type already exists'),
        ], [
            'value'                => __('Duration'),
            'value_for_author' => __('Duration For Authors'),
            'percentage'           => __('Percentage'),
        ]))->update();

        return redirect()->route($this->getRedirectRoute())->withSuccess(__('Successfully updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Urgency $urgency)
    {
        $redirect = redirect()->route($this->getRedirectRoute());

        try {
            $urgency->delete();
            $redirect->withSuccess(__('Successfully deleted'));
        } catch (\Illuminate\Database\QueryException$e) {

            $redirect->withFail(__('You cannot delete the urgency as it is associated with one or multiple orders'));
        } catch (\Exception$e) {
            $redirect->withFail(__('Could not perform the requested action'));
        }

        return $redirect;
    }

    private function getRedirectRoute()
    {
        return 'admin.urgencies.index';
    }
}
