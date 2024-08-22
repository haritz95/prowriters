<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return inertia('Author/Announcements/Index', [
            'data'          => [
                'title' => __('Announcements'),
            ],
            'filters'       => $request->only('filters'),
            'announcements' => Announcement::query()
                ->when($request->filled('filters.search'), function ($q) use ($request) {
                    return $q->where('title', 'like', '%' . $request->filters['search'] . '%');
                })
                ->orderBy('id', 'DESC')
                ->paginate(config('app.pagination.per_page'))
                ->withQueryString(),
        ]);
    }

    public function show(Announcement $announcement)
    {
        return inertia('Author/Announcements/Show', [
            'data' => [
                'title'        => __('Announcement'),
            ],
            'announcement' => $announcement,
        ]);
    }

}
