<?php

namespace App\Http\Controllers\Admin\Business;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAuthorLevelRequest;
use App\Models\Business\AuthorLevel;
use Illuminate\Http\Request;

class AuthorLevelController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return inertia('Admin/Business/AuthorLevels/Index', [
            'data'              => [
                'title' => __('Author Levels'),
            ],
            'filters'           => $request->only('filters'),
            'author_levels' => AuthorLevel::query()
                ->when($request->filled('filters.search'), function ($q) use ($request) {
                    return $q->where('name', 'like', '%' . $request->filters['search'] . '%');
                })
                ->orderBy('numeric_value', 'ASC')
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
        return inertia()->modal('Admin/Business/AuthorLevels/Create', [
            'data' => [
                'title'     => __('Add Author Level'),
                'dropdowns' => AuthorLevel::dropdowns(),
            ],
        ])->baseRoute($this->getRedirectRoute());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAuthorLevelRequest $request)
    {
        $data = $request->validated();
        AuthorLevel::create(array_merge($data, $this->markPopularAndDefault($data['is_default'], $data['is_popular'])));

        return redirect()->route($this->getRedirectRoute())->withSuccess(__('Successfully created'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(AuthorLevel $authorLevel)
    {
        return inertia()->modal('Admin/Business/AuthorLevels/Create', [
            'data'            => [
                'title'     => __('Edit Author Level'),
                'dropdowns' => AuthorLevel::dropdowns(),
            ],
            'existing_record' => $authorLevel,
        ])->baseRoute($this->getRedirectRoute());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreAuthorLevelRequest $request, AuthorLevel $authorLevel)
    {
        $data = $request->validated();

        $authorLevel->fill($data)->update();

        $boolean_columns = null;

        if ($data['is_popular']) {
            $boolean_columns['is_popular'] = null;
        }

        if ($data['is_default']) {
            $boolean_columns['is_default'] = null;
        }
        if ($boolean_columns) {
            AuthorLevel::where('id', '<>', $authorLevel->id)->update($boolean_columns);
        }

        return redirect()->route($this->getRedirectRoute())->withSuccess(__('Successfully updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(AuthorLevel $authorLevel)
    {
        $redirect = redirect()->route($this->getRedirectRoute());

        if ($authorLevel->is_default == true) {
            $redirect->withFail(__('Cannot delete a default level'));
        } else if ($authorLevel->is_popular == true) {
            $redirect->withFail(__('Cannot delete a popular level'));
        } else {
            try {
                $authorLevel->delete();
                $redirect->withSuccess(__('Successfully deleted'));
            } catch (\Illuminate\Database\QueryException$e) {
                $redirect->withFail(__('You cannot delete the item as it is associated with one or multiple orders'));
            } catch (\Exception$e) {
                $redirect->withFail(__('Could not perform the requested action'));
            }
        }

        return $redirect;
    }

    private function getRedirectRoute()
    {
        return 'admin.authorLevels.index';
    }

    private function markPopularAndDefault($is_default, $is_popular)
    {
        $data = [
            'is_default' => $is_default,
            'is_popular' => $is_popular,
        ];
        $is_default_count = AuthorLevel::where('is_default', true)->count();
        $is_popular_count = AuthorLevel::where('is_popular', true)->count();

        if ($is_default_count == 0) {
            $data['is_default'] = true;
        }
        if ($is_popular_count == 0) {
            $data['is_popular'] = true;
        }

        return $data;
    }
}
