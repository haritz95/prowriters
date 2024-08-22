<?php

namespace App\Http\Controllers\Admin\Website;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWebsitePageRequest;
use App\Models\Website\WebsitePage;
use Illuminate\Http\Request;

class WebsitePageController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $language)
    {
        return inertia('Admin/Manage/WebsitePages/Index', [
            'data'             => [
                'title' => __('Website Pages'),
            ],
            'content_language' => $language,
            'filters'          => $request->only('filters'),
            'website_pages'    => WebsitePage::where('type', WebsitePage::TYPE_CUSTOM)
                ->where('locale', $language)
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
        return inertia('Admin/Manage/WebsitePages/Create', [
            'data'             => [
                'title'     => __('Add Page'),
                'dropdowns' => [
                    'image_positions' => WebsitePage::getImagePositions(),
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
    public function store(StoreWebsitePageRequest $request, $language)
    {
        $data           = $request->validated();
        $data['locale'] = $language;
        $data['type']   = WebsitePage::TYPE_CUSTOM;
        WebsitePage::create($data);
        return redirect()->route($this->getRedirectRoute(), $language)->withSuccess(__('Successfully created'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($language, WebsitePage $websitePage)
    {
        return inertia('Admin/Manage/WebsitePages/Create', [
            'data'             => [
                'title'     => __('Edit Website Page'),
                'dropdowns' => [
                    'image_positions' => WebsitePage::getImagePositions(),
                ],
            ],
            'existing_record'  => $websitePage,
            'content_language' => $language,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreWebsitePageRequest $request, $language, $id)
    {
        $data = $request->validated();        
        WebsitePage::where('id', $id)->update($data);
        WebsitePage::clearCache($data['slug']);
        return redirect()->route($this->getRedirectRoute(), $language)->withSuccess(__('Successfully updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($language, WebsitePage $websitePage)
    {
        $redirect = redirect()->route($this->getRedirectRoute(), $language);
        try {
            WebsitePage::clearCache($websitePage->slug);
            $websitePage->delete();
            $redirect->withSuccess(__('Successfully deleted'));
        } catch (\Illuminate\Database\QueryException$e) {
            $redirect->withFail(__('You cannot delete the service as it is associated with one or multiple records'));
        } catch (\Exception$e) {
            $redirect->withFail(__('Could not perform the requested action'));
        }

        return $redirect;
    }

    private function getRedirectRoute()
    {
        return 'admin.manage.content.websitePages.index';
    }
}
