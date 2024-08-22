<?php

namespace App\Http\Controllers\Admin\Website;

use App\Http\Controllers\Controller;
use App\Models\Website\Faq\FaqCategory;
use App\Models\Website\Faq\FaqQuestion;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FaqQuestionController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $language)
    {
        return inertia('Admin/Manage/Faq/Questions/Index', [
            'data'             => [
                'title' => __('FAQ Questions'),
            ],
            'content_language' => $language,
            'filters'          => $request->only('filters'),
            'faqQuestions'     => FaqQuestion::query()
                ->when($request->filled('filters.search'), function ($q) use ($request) {
                    return $q->where('title', 'like', '%' . $request->filters['search'] . '%');
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
        return inertia()->modal('Admin/Manage/Faq/Questions/Create', [
            'data'             => [
                'title'     => __('Add FAQ Question'),
                'dropdowns' => [
                    'categories' => FaqCategory::all(),
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
        $data = $request->validate([
            'title'       => 'required|unique:faq_questions',
            'description' => 'required',
            'categories'  => 'required|array',
        ]);

        // Saving Data
        $data['locale'] = $language;
        $faq            = FaqQuestion::create($data);
        $faq->categories()->attach($request->categories);
        return redirect()->route($this->getRedirectRoute(), $language)->withSuccess(__('Successfully created'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($language, FaqQuestion $faqQuestion)
    {
        $faqQuestion->categories = $faqQuestion->categories()->pluck('faq_category_id')->toArray();

        return inertia()->modal('Admin/Manage/Faq/Questions/Create', [
            'data'             => [
                'title'     => __('Edit FAQ Question'),
                'dropdowns' => [
                    'categories' => FaqCategory::all(),
                ],
            ],
            'content_language' => $language,
            'existing_record'  => $faqQuestion,
        ])->baseRoute($this->getRedirectRoute(), $language);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $language, FaqQuestion $faqQuestion)
    {
        $data = $request->validate([
            'title'       => [
                'required',
                Rule::unique('faq_questions')->ignore($faqQuestion->id),
            ],
            'description' => 'required',
            'categories'  => 'required|array',
        ]);

        // Saving Data
        $faqQuestion->update($data);
        $faqQuestion->categories()->sync($request->categories);

        return redirect()->route($this->getRedirectRoute(), $language)->withSuccess(__('Successfully updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($language, FaqQuestion $faqQuestion)
    {
        $faqQuestion->delete();
        return redirect()->route($this->getRedirectRoute(), $language)->withSuccess(__('Successfully deleted'));

    }

    private function getRedirectRoute()
    {
        return 'admin.manage.content.faqQuestions.index';
    }

}
