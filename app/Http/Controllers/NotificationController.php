<?php
namespace App\Http\Controllers;

use App\Enums\UserType;
use Illuminate\Http\Request;

class NotificationController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        if ($user->type == UserType::ADMIN) {
            $prefix = 'admin';
        } else if ($user->type == UserType::AUTHOR) {
            $prefix = 'author';
        } else {
            $prefix = 'customer';
        }
        return redirect()->route($prefix . '.notifications.index');

    }

    public function get_unread_notifications()
    {
        $notifications = auth()->user()->unreadNotifications()
            ->orderBy('created_at', 'DESC')->take(15)->get();
        $records = [];
        if (count($notifications) > 0) {
            foreach ($notifications as $notification) {

                $data           = $notification->data;
                $data['moment'] = $notification->created_at->diffForHumans();
                $data['url']    = route('notification_redirect_url', $notification->id);
                $records[]      = $data;
            }
        }

        $pushNotfiication = auth()->user()->pushNotification()->get();

        if ($pushNotfiication->count() > 0) {
            $pushNotfiication->first()->delete();
        }

        return response()->json($records);
    }

    public function redirect_url($id)
    {
        if ($id) {
            $notification = auth()->user()->notifications->where('id', $id);

            if (count($notification) > 0) {
                $notification = $notification->first();
                $url          = $notification->data['url'];
                $notification->markAsRead();

                return redirect()->to($url);
            }

        }
        abort(404);
    }

    public function mark_all_notification_as_read()
    {
        auth()->user()->unreadNotifications()->update(['read_at' => now()]);

    }

    public function push_notification()
    {
        $notfiication = auth()->user()->pushNotification()->get();

        if ($notfiication->count() > 0) {
            return $notfiication->first()->number;
        }

        return 0;
    }
}
