<?php

namespace App\Http\Controllers\Admin\Website;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWebsiteMenuRequest;
use App\Models\Website\WebsiteMenu;
use Illuminate\Http\Request;

class WebsiteMenuController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $language)
    {
        return inertia('Admin/Manage/WebsiteMenus/Index', [
            'data'             => [
                'title' => __('Website Menu Items'),
            ],
            'content_language' => $language,
            'filters'          => $request->only('filters'),
            'website_menus'    => WebsiteMenu::where('locale', $language)
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
        return inertia()->modal('Admin/Manage/WebsiteMenus/Create', [
            'data'             => [
                'title'     => __('Add Menu Item'),
                'dropdowns' => WebsiteMenu::dropdowns($language),
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
    public function store(StoreWebsiteMenuRequest $request, $language)
    {
        $data           = $request->validated();
        $data['locale'] = $language;
        WebsiteMenu::create($data);
        return redirect()->route($this->getRedirectRoute(), $language)->withSuccess(__('Successfully created'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($language, WebsiteMenu $websiteMenu)
    {
        return inertia()->modal('Admin/Manage/WebsiteMenus/Create', [
            'data'             => [
                'title'     => __('Edit Website Menu'),
                'dropdowns' => WebsiteMenu::dropdowns($language),
            ],
            'existing_record'  => $websiteMenu,
            'content_language' => $language,
        ])->baseRoute($this->getRedirectRoute(), $language);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreWebsiteMenuRequest $request, $language, $id)
    {
        $data = $request->validated();
        WebsiteMenu::where('id', $id)->update($data);
        return redirect()->route($this->getRedirectRoute(), $language)->withSuccess(__('Successfully updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($language, WebsiteMenu $websiteMenu)
    {
        $redirect = redirect()->route($this->getRedirectRoute(), $language);
        try {
            $websiteMenu->delete();
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
        return 'admin.manage.content.websiteMenus.index';
    }
}
