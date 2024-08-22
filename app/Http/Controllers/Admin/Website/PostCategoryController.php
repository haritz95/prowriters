<?php

namespace App\Http\Controllers\Admin\Website;

use App\Http\Controllers\Controller;
use App\Models\Website\Blog\PostCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PostCategoryController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $language)
    {
        return inertia('Admin/Manage/Blog/Categories/Index', [
            'data'             => [
                'title' => __('Blog Post Categories'),
            ],
            'content_language' => $language,
            'filters'          => $request->only('filters'),
            'postCategories'   => PostCategory::query()
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
        return inertia()->modal('Admin/Manage/Blog/Categories/Create', [
            'data'             => [
                'title' => __('Add Post Category'),
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
            'name'      => 'required|unique:post_categories',
            'meta_tags' => 'required',
        ]);

        // Saving Data
        $data['locale'] = $language;
        PostCategory::create($data);
        return redirect()->route($this->getRedirectRoute(), $language)->withSuccess(__('Successfully created'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($language, PostCategory $postCategory)
    {
        return inertia()->modal('Admin/Manage/Blog/Categories/Create', [
            'data'             => [
                'title' => __('Edit Post Category'),
            ],
            'content_language' => $language,
            'existing_record'  => $postCategory,
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
            'name'      => [
                'required',
                Rule::unique('post_categories')->ignore($id),
            ],
            'meta_tags' => 'required',
        ]);

        // Saving Data
        PostCategory::where('id', $id)->where('locale', $language)->update($data);

        return redirect()->route($this->getRedirectRoute(), $language)->withSuccess(__('Successfully updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($language, PostCategory $postCategory)
    {
        $redirect = redirect()->route($this->getRedirectRoute(), $language);

        try {
            $postCategory->delete();
            $redirect->withSuccess(__('Successfully deleted'));
        } catch (\Illuminate\Database\QueryException$e) {
            $redirect->withFail(__('You cannot delete the service as it is associated with one or multiple posts'));
        } catch (\Exception$e) {
            $redirect->withFail(__('Could not perform the requested action'));
        }
        return $redirect;
    }

    private function getRedirectRoute()
    {
        return 'admin.manage.content.postCategories.index';
    }

}
