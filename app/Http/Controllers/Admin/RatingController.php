<?php

namespace App\Http\Controllers\Admin;

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
        return inertia('Admin/Reports/Ratings', [

            'data'    => function () {
                // ALWAYS included on first visit
                // OPTIONALLY included on partial reloads
                // ONLY evaluated when needed
                return [
                    'title' => __('Ratings given by customer'),
                ];
            },            
            'ratings' => Rating::with(['task' => function ($q) {
                $q->select(['id', 'uuid', 'number', 'title', 'service_id', 'customer_id', 'author_id']);
            },
                'task.customer'                   => function ($customerQuery) {
                    $customerQuery->select(['id', 'uuid', 'code', 'first_name', 'last_name']);
                },
                'task.author'                 => function ($authorQuery) {
                    $authorQuery->select(['id', 'uuid', 'code', 'first_name', 'last_name']);
                },
            ])
                ->orderBy('id', 'DESC')
                ->paginate(config('app.pagination.per_page'))
                ->withQueryString(),

        ]);
    }

}
