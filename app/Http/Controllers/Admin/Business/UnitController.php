<?php

namespace App\Http\Controllers\Admin\Business;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUnitRequest;
use App\Models\Business\Assignment;
use App\Models\Business\Service;
use App\Models\Business\Urgency;
use Illuminate\Http\Request;

class UnitController extends Controller
{

    public function create(Service $service, Assignment $assignment)
    {
        return inertia()->modal('Admin/Business/Units/Create', [
            'data' => [
                'title'                => __('Quantity and Price setup for') . ' ' . $assignment->name,
                'urls'                 => [
                    'submit_form' => route('admin.assignments.units.store', ['service' => $service->slug, 'assignment' => $assignment->slug]),
                ],
                'units'                => $assignment->units()->get(),
                'url_query_parameters' => $this->getQueryParameterPreviousUrl(),
                'dropdowns'            => [
                    'urgencies' => Urgency::all(),
                ],
            ],
        ])->baseRoute('admin.assignments.index', ['service' => $service->slug, 'assignment' => $assignment->slug]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUnitRequest $request, Service $service, Assignment $assignment)
    {
        $data = $request->validated();

        $existing_ids = collect($data['units'])->pluck('id')->filter()->toArray();

        $assignment->units()->whereNotIn('id', $existing_ids)->delete();

        if (isset($data['units']) && count($data['units']) > 0) {
            foreach ($data['units'] as $row) {
                if (isset($row['id']) && $row['id']) {
                    $assignment->units()->where('id', $row['id'])->update($row);
                } else {
                    $assignment->units()->create($row);
                }
            }
        }

        return redirect()->back()->withSuccess(__('Successfully created'));

    }

}
