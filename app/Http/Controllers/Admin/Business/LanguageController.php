<?php

namespace App\Http\Controllers\Admin\Business;

use App\Http\Controllers\Controller;
use App\Models\Business\Language;
use App\Models\Business\Service;
use Illuminate\Http\Request;

class LanguageController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Service $service)
    {
        return inertia('Admin/Business/Languages/Index', [
            'data'      => [
                'title'   => __('Languages'),
                'service' => $service,
            ],
            'filters'   => $request->only('filters'),
            'languages' => Language::query()
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
        return inertia()->modal('Admin/Business/Languages/Create', [
            'data' => [
                'title' => __('Add Language'),
                'urls'  => [
                    'submit_form' => route('admin.languages.store', $service->slug),
                ],
            ],
        ])->baseRoute('admin.languages.index', ['service' => $service->slug]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Service $service)
    {
        Language::create($request->validate([
            'name'       => 'required|string|max:192|unique:languages,name',
            'percentage' => config('app.validation_rules.percentage'),
        ]));

        return redirect()->route('admin.languages.index', ['service' => $service->slug])->withSuccess(__('Successfully created'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Service $service, Language $language)
    {
        return inertia()->modal('Admin/Business/Languages/Create', [
            'data'            => [
                'title' => __('Edit Language'),
                'urls'  => [
                    'submit_form' => route('admin.languages.update', ['service' => $service->slug, 'language' => $language->slug]),
                ],
            ],
            'existing_record' => $language,
        ])->baseRoute('admin.languages.index', ['service' => $service->slug]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Service $service, Language $language)
    {
        $language->fill($request->validate([
            'name'       => 'required|string|max:192|unique:languages,name,' . $language->id,
            'percentage' => config('app.validation_rules.percentage'),

        ]))->update();

        return redirect()->route('admin.languages.index', ['service' => $service->slug])->withSuccess(__('Successfully updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Service $service, Language $language)
    {
        $redirect = redirect()->route('admin.languages.index', ['service' => $service->slug]);

        try {
            $language->delete();
            $redirect->withSuccess(__('Successfully deleted'));
        } catch (\Illuminate\Database\QueryException $e) {
            $redirect->withFail(__('You cannot delete the service as it is associated with one or multiple orders'));
        } catch (\Exception $e) {
            $redirect->withFail(__('Could not perform the requested action'));
        }
        return $redirect;
    }

}
