<?php

namespace App\Http\Controllers\Admin\Website;

use App\Http\Controllers\Controller;
use App\Models\Website\WebsiteTestimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $language)
    {       
        return inertia('Admin/Manage/Testimonials/Index', [
            'data'             => [
                'title' => __('Client Testimonials'),
            ],
            'content_language' => $language,
            'filters'          => $request->only('filters'),
            'testimonials'     => WebsiteTestimonial::query()
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
    public function create($language)
    {
        return inertia()->modal('Admin/Manage/Testimonials/Create', [
            'data'             => [
                'title'     => __('Add Client Testimonial'),
                'dropdowns' => [
                    'ratings' => WebsiteTestimonial::ratings(),
                ],
            ],
            'content_language' => $language,
        ])->baseRoute($this->getRedirectRoute(), $language);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $language)
    {
        $request->validate([
            'name'     => 'required',
            'avatar'   => 'required',
            'position' => 'required|max:192',
            'rating'   => 'required|numeric',
            'comment'  => 'required|max:1000',
        ]);

        // Saving Data
        $request['locale'] = $language;
        WebsiteTestimonial::create($request->all());
        return redirect()->route($this->getRedirectRoute(), $language)->withSuccess(__('Successfully created'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($language, WebsiteTestimonial $testimonial)
    {
        return inertia()->modal('Admin/Manage/Testimonials/Create', [
            'data'             => [
                'title'     => __('Edit Client Testimonial'),
                'dropdowns' => [
                    'ratings' => WebsiteTestimonial::ratings(),
                ],
            ],
            'content_language' => $language,
            'existing_record'  => $testimonial,
        ])->baseRoute($this->getRedirectRoute(), $language);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $language, $id)
    {
        $data = $request->validate([
            'name'     => 'required',
            'avatar'   => 'required',
            'rating'   => 'required|numeric',
            'position' => 'required|max:192',
            'comment'  => 'required|max:1000',
        ]);

        // Saving Data
        WebsiteTestimonial::where('id', $id)->where('locale', $language)->update($data);

        return redirect()->route($this->getRedirectRoute(), $language)->withSuccess(__('Successfully updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($language, WebsiteTestimonial $testimonial)
    {
        $testimonial->delete();
        return redirect()->route($this->getRedirectRoute(), $language)->withSuccess(__('Successfully deleted'));

    }

    private function getRedirectRoute()
    {
        return 'admin.manage.content.testimonials.index';
    }

}
