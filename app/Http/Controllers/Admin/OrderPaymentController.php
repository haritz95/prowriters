<?php

namespace App\Http\Controllers\Admin;

use App\Models\Orders\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class OrderPaymentController extends Controller
{
    public function index(Request $request, Order $order)
    {
        $order->load(['status', 'details']);

    
        $data['urls'] = [
            'new_message' => route('admin.orders.discussions.create', $order->uuid),
        ];

        return inertia('Admin/Orders/Financial', [           
            'order' => $order,            
            'data' => $data,
        ]);
    }

    public function create(Order $order)
    {
        $order->load('status');

        return inertia('Admin/Orders/Discussions/Create', [
            'tab' => 'discussions',
            'order' => $order,
            'data' => [
                'config' => $this->orderMessageService->getConfigForCreateMessage(route('admin.orders.discussions.store', $order->uuid)),
                'urls' => [
                    'message_list' => route('admin.orders.discussions.index', $order->uuid),
                ]
            ]
        ]);
    }

    function store(StoreOrderMessageRequest $request, Order $order)
    {
        $this->orderMessageService->store($order, $request, TRUE);

        return redirect()->route('admin.orders.discussions.index', $order->uuid)->withSuccess(__('Message sent successfully'));
    }
}
