<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait PushNotificationTrait
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function notifications(Request $request)
    {
        return inertia('NotificationList', [
            'data' => [
                'title'         => __('Notifications'),

            ],
            'notifications' => auth()->user()
                ->notifications()
                ->paginate(config('app.pagination.per_page'))

            // ->withQueryString()
            //     ->through(fn ($order) => [
            //        'uuid' => $order->uuid,
            //        'number' => $order->number,
            //        'title' => Str::limit($order->title, 60),
            //        'status' => $order->status,
            //        'service' => $order->service->name,
            //       'created_at' => $order->created_at,
            //        'dead_line' => $order->dead_line,
            //        'total' => format_currency($order->total),
            //        'link' => route('admin.orders.show', $order->uuid),
            //       'customer' => $order->customer->full_name,
            //       'customer_profile_link' => route('admin.customers.show', $order->customer->uuid),
            //        'freelancer' => $order->assignee ? $order->assignee->full_name : NULL,
            //        'freelancer_profile_link' => $order->assignee ? route('admin.freelancers.show', $order->assignee->uuid) : NULL,
            //     ]),
            ,
        ]);
    }

    // public function paginate(Request $request)
    // {

    //     return Datatables::of(auth()->user()->notifications)
    //         ->addColumn('description', function ($notification) {
    //             return anchor_link($notification->data['message'], route('notification_redirect_url', $notification->id));
    //         })
    //         ->addColumn('status', function ($notification) {
    //             return ($notification->read_at) ? __('Read') : __('Unread');
    //         })
    //         ->editColumn('created_at', function ($notification) {
    //             return $notification->created_at->diffForHumans();
    //         })

    //         ->rawColumns([
    //             'description',
    //         ])

    //         ->make(true);
    // }
}
