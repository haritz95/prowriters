<?php

namespace App\Http\Controllers\Customer;

use App\Enums\ServiceType;
use Illuminate\Http\Request;
use App\Models\ProjectManagement\Project;
use App\Http\Controllers\Controller;
use App\Models\Business\Service;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return inertia('Customer/Projects/Index', [
            'data' => [
                'title' => __('Projects'),
                'urls' => [
                    'search' => route('customer.projects.index'),
                ],
            ],
            'filters' => $request->only('filters'),
            'projects' =>  Project::with(['service'])
                ->when($request->filled('filters.search'), function ($q) use ($request) {
                    return $q->where('name', $request->filters['search']);
                })
                ->orderBy('id', 'DESC')
                ->paginate(config('app.pagination.per_page'))
                ->withQueryString()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return inertia()->modal('Customer/Projects/Create', [
            'data' => [
                'title'     => __('Add project'),
                'urls'      => [
                    'submit_form' => route('customer.projects.store'),
                ],
                'dropdowns' => [
                    'services' => Service::where('id', ServiceType::CONTENT_WRITING)->active()->get(),                    
                ],
            ],
        ])->baseRoute('customer.projects.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tasks\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tasks\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tasks\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tasks\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        //
    }
}
