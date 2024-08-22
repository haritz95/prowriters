<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Models\ProjectManagement\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return inertia('Author/RatingsReceived', [

            'data'    => function () {
                // ALWAYS included on first visit
                // OPTIONALLY included on partial reloads
                // ONLY evaluated when needed
                return [
                    'title' => __('Ratings Received'),
                ];
            },
            'filters' => $request->only('filters'),
            'ratings' => Rating::withWhereHas('task', function ($q) {
                $q->select(['id', 'uuid', 'number'])->where('author_id', auth()->user()->id);
            })
                ->orderBy('id', 'DESC')
                ->paginate(config('app.pagination.per_page'))
                ->withQueryString(),

        ]);
    }

}
