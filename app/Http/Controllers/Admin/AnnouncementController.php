<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserType;
use App\Events\NewAnnouncementEvent;
use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnnouncementController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return inertia('Admin/Announcements/Index', [
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return inertia('Admin/Announcements/Create', [
            'data' => [
                'title' => __('Create Announcement'),
            ],

        ]);
    }

    public function store(Request $request)
    {
        
        $data = $request->validate([
            'title'    => 'required|max:192',
            'content'  => 'required',
            'inactive' => 'nullable|boolean',
        ]);

        DB::beginTransaction();

        try {

            $announcement = Announcement::create([
                'target_user_type' => UserType::AUTHOR,
                'title'            => $data['title'],
                'content'          => $data['content'],
                'user_id'          => auth()->user()->id,
                'inactive'         => ($data['inactive']) ? TRUE : NULL,
            ]);

            event(new NewAnnouncementEvent($announcement, User::authors()->get()));

            DB::commit();

            return redirect()->route('admin.announcements.index')->withSuccess(__('Successfully sent'));

        } catch (\Exception $e) {
            DB::rollback();
            _debug($e);
            return redirect()->back()->withFail(__('Could not perform the requested action'));

        }

    }

    public function show(Announcement $announcement)
    {
        return inertia('Admin/Announcements/Show', [
            'data' => [
                'title'        => __('Announcement'),

            ],
            'announcement' => $announcement,
        ]);
    }

    public function edit(Announcement $announcement)
    {
        return inertia('Admin/Announcements/Create', [
            'data'            => [
                'title' => __('Edit Announcement') . ' ' . $announcement->number,
            ],
            'existing_record' => $announcement,
        ]);
    }

    public function update(Request $request, Announcement $announcement)
    {
        $data = $request->validate([
            'title'    => 'required|max:192',
            'content'  => 'required',
            'inactive' => 'nullable|boolean',
        ]);

        $data['inactive'] = ($data['inactive']) ? TRUE : NULL;

        $announcement->fill($data)->update();

        return redirect()->route('admin.announcements.show', $announcement->uuid)->withSuccess(__('Successfully updated'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Announcement $announcement)
    {
        $redirect = redirect()->route('admin.announcements.index');

        try {
            $announcement->delete();
            $redirect->withSuccess(__('Successfully deleted'));
        } catch (\Illuminate\Database\QueryException $e) {
            $redirect->withFail(__('You cannot delete the item as it is associated with one or multiple records'));
        } catch (\Exception $e) {
            $redirect->withFail(__('Could not perform the requested action'));
        }

        return $redirect;
    }

}
