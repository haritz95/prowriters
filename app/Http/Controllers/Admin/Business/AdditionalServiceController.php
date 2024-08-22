<?php

namespace App\Http\Controllers\Admin\Business;

use App\Enums\PriceType;
use App\Http\Controllers\Controller;
use App\Models\Business\AdditionalService;
use App\Models\Business\Service;
use Illuminate\Http\Request;

class AdditionalServiceController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return inertia('Admin/Business/AdditionalServices/Index', [
            'data'                => [
                'title'       => __('Additional Services'),
                'price_types' => PriceType::getNames(),
            ],
            'filters'             => $request->only('filters'),
            'additional_services' => AdditionalService::query()
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
        return inertia()->modal('Admin/Business/AdditionalServices/Create', [
            'data' => [
                'title'                => __('Add Additional Service'),
                'per_entered_quantity' => PriceType::PER_ENTERED_QUANTITY,
                'dropdowns'            => [
                    'price_types' => PriceType::asDropdown(),
                    'services'    => Service::all(),
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
        AdditionalService::create($request->validate([
            'name'                       => 'required|string|max:192|unique:additional_services,name',
            'type'                       => 'required',
            'description'                => 'required|string|max:255',
            'per_entered_quantity_label' => 'required_if:type,' . PriceType::PER_ENTERED_QUANTITY,
            'price'                      => 'required',
            'services'                   => 'required|array',
        ], [
            'per_entered_quantity_label.required_if' => __('The Quantity Label field is required'),
        ]))->services()->attach($request->services);

        return redirect()->route($this->getRedirectRoute())->withSuccess(__('Successfully created'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(AdditionalService $additionalService)
    {
        $additionalService['services'] = $additionalService->services()->pluck('service_id');

        return inertia()->modal('Admin/Business/AdditionalServices/Create', [
            'data'            => [
                'title'                => __('Edit Additional Service'),
                'per_entered_quantity' => PriceType::PER_ENTERED_QUANTITY,
                'dropdowns'            => [
                    'price_types' => PriceType::asDropdown(),
                    'services'    => Service::all(),
                ],
            ],
            'existing_record' => $additionalService,
        ])->baseRoute($this->getRedirectRoute());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AdditionalService $additionalService)
    {
        $data = $request->validate([
            'name'                       => 'required|string|max:192|unique:additional_services,name,' . $additionalService->id,
            'type'                       => 'required',
            'description'                => 'required|string|max:255',
            'per_entered_quantity_label' => 'required_if:type,' . PriceType::PER_ENTERED_QUANTITY,
            'price'                      => 'required',
        ], [
            'per_entered_quantity_label.required_if' => __('The Quantity Label field is required'),
        ]);
        $additionalService->fill($data)->update();

        $additionalService->services()->sync($request->services);

        return redirect()->route($this->getRedirectRoute())->withSuccess(__('Successfully updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(AdditionalService $additionalService)
    {
        $redirect = redirect()->route($this->getRedirectRoute());
        try {
            $additionalService->delete();
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
        return 'admin.additionalServices.index';
    }
}
