<?php

namespace App\Http\Controllers\Admin\Website;

use App\Http\Controllers\Controller;
use App\Models\Website\Faq\FaqCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FaqCategoryController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $language)
    {
        return inertia('Admin/Manage/Faq/Categories/Index', [
            'data'             => [
                'title' => __('FAQ Categories'),
            ],
            'content_language' => $language,
            'filters'          => $request->only('filters'),
            'faqCategories'    => FaqCategory::query()
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
        return inertia()->modal('Admin/Manage/Faq/Categories/Create', [
            'data'             => [
                'title' => __('Add FAQ Category'),
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
        $data = $request->validate([
            'name' => 'required|unique:faq_categories',
        ]);

        // Saving Data
        $data['locale'] = $language;
        FaqCategory::create($data);
        return redirect()->route($this->getRedirectRoute(), $language)->withSuccess(__('Successfully created'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($language, FaqCategory $faqCategory)
    {
        return inertia()->modal('Admin/Manage/Faq/Categories/Create', [
            'data'             => [
                'title' => __('Edit FAQ Category'),
            ],
            'content_language' => $language,
            'existing_record'  => $faqCategory,
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
            'name' => [
                'required',
                Rule::unique('faq_categories')->ignore($id),
            ],
        ]);

        // Saving Data
        FaqCategory::where('id', $id)->where('locale', $language)->update($data);

        return redirect()->route($this->getRedirectRoute(), $language)->withSuccess(__('Successfully updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($language, FaqCategory $faqCategory)
    {
        $faqCategory->delete();
        return redirect()->route($this->getRedirectRoute(), $language)->withSuccess(__('Successfully deleted'));

    }

    private function getRedirectRoute()
    {
        return 'admin.manage.content.faqCategories.index';
    }

}
