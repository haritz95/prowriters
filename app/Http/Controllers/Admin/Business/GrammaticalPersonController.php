<?php

namespace App\Http\Controllers\Admin\Business;

use App\Http\Controllers\Controller;
use App\Models\Business\GrammaticalPerson;
use App\Models\Business\Service;
use Illuminate\Http\Request;

class GrammaticalPersonController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Service $service)
    {
        return inertia('Admin/Business/GrammaticalPeople/Index', [
            'data'               => [
                'title'   => __('Grammatical People'),
                'service' => $service,
            ],
            'filters'            => $request->only('filters'),
            'grammatical_people' => GrammaticalPerson::query()
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
        return inertia()->modal('Admin/Business/GrammaticalPeople/Create', [
            'data' => [
                'title' => __('Add Grammatical Person'),
                'urls'  => [
                    'submit_form' => route('admin.grammaticalPeople.store', $service->slug),
                ],
            ],
        ])->baseRoute('admin.grammaticalPeople.index', ['service' => $service->slug]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Service $service)
    {
        GrammaticalPerson::create($request->validate([
            'name' => 'required|string|max:192|unique:grammatical_people,name',
        ]));

        return redirect()->route('admin.grammaticalPeople.index', ['service' => $service->slug])->withSuccess(__('Successfully created'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Service $service, GrammaticalPerson $grammaticalPerson)
    {
        return inertia()->modal('Admin/Business/GrammaticalPeople/Create', [
            'data'            => [
                'title' => __('Edit Grammatical Person'),
                'urls'  => [
                    'submit_form' => route('admin.grammaticalPeople.update', ['service' => $service->slug, 'grammaticalPerson' => $grammaticalPerson->slug]),
                ],
            ],
            'existing_record' => $grammaticalPerson,
        ])->baseRoute('admin.grammaticalPeople.index', ['service' => $service->slug]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Service $service, GrammaticalPerson $grammaticalPerson)
    {
        $grammaticalPerson->fill($request->validate([
            'name' => 'required|string|max:192|unique:grammatical_people,name,' . $grammaticalPerson->id,
        ]))->update();

        return redirect()->route('admin.grammaticalPeople.index', ['service' => $service->slug])->withSuccess(__('Successfully updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Service $service, GrammaticalPerson $grammaticalPerson)
    {
        $redirect = redirect()->route('admin.grammaticalPeople.index', ['service' => $service->slug]);
        try {
            $grammaticalPerson->delete();
            $redirect->withSuccess(__('Successfully deleted'));
        } catch (\Illuminate\Database\QueryException $e) {
            $redirect->withFail(__('You cannot delete the item as it is associated with one or multiple orders'));
        } catch (\Exception $e) {
            $redirect->withFail(__('Could not perform the requested action'));
        }

        return $redirect;
    }

}
