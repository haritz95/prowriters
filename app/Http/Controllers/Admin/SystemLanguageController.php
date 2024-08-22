<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Locale\SystemLanguage;
use Illuminate\Http\Request;

class SystemLanguageController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return inertia('Admin/Settings/Languages/Index', [
            'data'      => [
                'title' => __('Languages'),
            ],
            'filters'   => $request->only('filters'),
            'languages' => SystemLanguage::query()
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
        return inertia()->modal('Admin/Settings/Languages/Create', [
            'data' => [
                'title'     => __('Add System Language'),
                'dropdowns' => SystemLanguage::dropdowns(),
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
        $data = $request->validate([
            'iso_code'         => 'required|string|min:2|max:2',
            'name'             => 'required|string|max:192|unique:system_languages,name',
            'country_code'     => 'nullable|string|min:2|max:2',
            'layout_direction' => 'nullable|in:ltr,rtl',
            'is_default'       => 'nullable|boolean',
        ]);

        $is_default_count = SystemLanguage::where('is_default', true)->count();

        if ($is_default_count == 0) {
            $data['is_default'] = true;
        } else if ($is_default_count > 0 && $data['is_default'] == true) {
            SystemLanguage::where('is_default', true)->update(['is_default' => null]);
        }

        SystemLanguage::create($data);

        SystemLanguage::forgetCache();

        return redirect()->route($this->getRedirectRoute())->withSuccess(__('Successfully created'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(SystemLanguage $systemLanguage)
    {
        return inertia()->modal('Admin/Settings/Languages/Create', [
            'data'            => [
                'title'     => __('Edit System Language'),
                'dropdowns' => SystemLanguage::dropdowns(),
            ],
            'existing_record' => $systemLanguage,
        ])->baseRoute($this->getRedirectRoute());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SystemLanguage $systemLanguage)
    {
        $data = $request->validate([
            'iso_code'         => 'required|string|min:2|max:2',
            'name'             => 'required|string|max:192|unique:system_languages,name,' . $systemLanguage->id,
            'country_code'     => 'nullable|string|min:2|max:2',
            'layout_direction' => 'nullable|in:ltr,rtl',
            'is_default'       => 'nullable|boolean',

        ]);

        $defaultLanguage = SystemLanguage::select('id')->where('is_default', true)->get()->first();

        if ($data['is_default'] != true && $defaultLanguage && ($defaultLanguage->id == $systemLanguage->id)) {
            // Throw validation error bag
            return redirect()->back()->withErrors(['is_default' => __('Default language can not be changed')]);
        } else if ($data['is_default'] == true) {
            SystemLanguage::where('is_default', true)->update(['is_default' => null]);
        } else {
            $data['is_default'] = null;
        }

        $systemLanguage->fill($data)->update();

        SystemLanguage::forgetCache();

        return redirect()->route($this->getRedirectRoute())->withSuccess(__('Successfully updated'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(SystemLanguage $systemLanguage)
    {
        $redirect = redirect()->route($this->getRedirectRoute());
        if ($systemLanguage->is_default == true) {
            $redirect->withFail(__('Cannot delete a default language'));
        } else {
            try {
                $systemLanguage->delete();
                $redirect->withSuccess(__('Successfully deleted'));
            } catch (\Illuminate\Database\QueryException$e) {
                $redirect->withFail(__('You cannot delete the language as it is associated with one or multiple records'));
            } catch (\Exception$e) {
                $redirect->withFail(__('Could not perform the requested action'));
            }
        }

        return $redirect;
    }

    private function getRedirectRoute()
    {
        return 'admin.settings.systemLanguages.index';
    }
}
