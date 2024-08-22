<?php

namespace App\Http\Controllers\Admin\Website;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Models\Website\Blog\Post;
use App\Models\Website\Blog\PostCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PostController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $language)
    {
        return inertia('Admin/Manage/Blog/Posts/Index', [
            'data'             => [
                'title' => __('Blog Posts'),
            ],
            'content_language' => $language,
            'filters'          => $request->only('filters'),
            'posts'            => Post::query()
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
        return inertia('Admin/Manage/Blog/Posts/Create', [
            'data'             => [
                'title'     => __('Add Post'),
                'dropdowns' => [
                    'categories' => PostCategory::all(),
                ],
            ],
            'content_language' => $language,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePostRequest $request, $language)
    {
        $data = $request->validated();

        // Saving Data
        $data['locale'] = $language;
        $data['user_id'] = auth()->user()->id;
        $post           = Post::create($data);
        $post->categories()->attach($request->categories);
        return redirect()->route($this->getRedirectRoute(), $language)->withSuccess(__('Successfully created'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($language, Post $post)
    {
        $post->categories = $post->categories()->pluck('post_category_id')->toArray();

        return inertia('Admin/Manage/Blog/Posts/Create', [
            'data'             => [
                'title'     => __('Edit Blog Post'),
                'dropdowns' => [
                    'categories' => PostCategory::all(),
                ],
            ],
            'content_language' => $language,
            'existing_record'  => $post,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(StorePostRequest $request, $language, $id)
    {
        $data = $request->validated();

        // Saving Data
        $post = Post::find($id);
        $post->fill($data);
        $post->update();
        $post->categories()->sync($request->categories);

        return redirect()->route($this->getRedirectRoute(), $language)->withSuccess(__('Successfully updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($language, Post $post)
    {
        $post->delete();
        return redirect()->route($this->getRedirectRoute(), $language)->withSuccess(__('Successfully deleted'));

    }

    private function getRedirectRoute()
    {
        return 'admin.manage.content.posts.index';
    }

}
