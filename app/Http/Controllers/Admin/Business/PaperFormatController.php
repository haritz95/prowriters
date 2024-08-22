<?php

namespace App\Http\Controllers\Admin\Business;

use App\Http\Controllers\Controller;
use App\Models\Business\PaperFormat;
use App\Models\Business\Service;
use Illuminate\Http\Request;

class PaperFormatController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Service $service)
    {
        return inertia('Admin/Business/PaperFormats/Index', [
            'data'          => [
                'title' => __('Paper Formats'),
                'service' => $service,
            ],
            'filters'       => $request->only('filters'),
            'paper_formats' => PaperFormat::query()
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
        return inertia()->modal('Admin/Business/PaperFormats/Create', [
            'data' => [
                'title' => __('Add Paper Format'),
                'urls'  => [
                    'submit_form' => route('admin.paperFormats.store', $service->slug),
                ],
            ],
        ])->baseRoute('admin.paperFormats.index', ['service' => $service->slug]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Service $service)
    {
        PaperFormat::create($request->validate([
            'name' => 'required|string|max:192|unique:paper_formats,name',
        ]));

        return redirect()->route('admin.paperFormats.index', ['service' => $service->slug])->withSuccess(__('Successfully created'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Service $service, PaperFormat $paperFormat)
    {
        return inertia()->modal('Admin/Business/PaperFormats/Create', [
            'data'            => [
                'title' => __('Edit Paper Format'),
                'urls'  => [
                    'submit_form' => route('admin.paperFormats.update', ['service' => $service->slug, 'paperFormat' => $paperFormat->slug]),
                ],
            ],
            'existing_record' => $paperFormat,
        ])->baseRoute('admin.paperFormats.index', ['service' => $service->slug]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Service $service, PaperFormat $paperFormat)
    {
        $paperFormat->fill($request->validate([
            'name' => 'required|string|max:192|unique:paper_formats,name,' . $paperFormat->id,

        ]))->update();

        return redirect()->route('admin.paperFormats.index', ['service' => $service->slug])->withSuccess(__('Successfully updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Service $service, PaperFormat $paperFormat)
    {
        $redirect = redirect()->route('admin.paperFormats.index', ['service' => $service->slug]);
        try {
            $paperFormat->delete();
            $redirect->withSuccess(__('Successfully deleted'));
        } catch (\Illuminate\Database\QueryException $e) {
            $redirect->withFail(__('You cannot delete the service as it is associated with one or multiple orders'));
        } catch (\Exception $e) {
            $redirect->withFail(__('Could not perform the requested action'));
        }

        return $redirect;
    }

    private function getRedirectRoute()
    {
        return 'admin.paperFormats.index';
    }
}
