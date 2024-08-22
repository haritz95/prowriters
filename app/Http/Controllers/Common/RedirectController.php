<?php

namespace App\Http\Controllers\Common;

use App\Models\Orders\Order;
use App\Http\Controllers\Controller;


class RedirectController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function order(Order $order)
    {
        switch (auth()->user()->type == 'author') {
            case 'author':
                return redirect()->route('author.jobs.show', $order->uuid);
                break;
            case 'customer':
                return redirect()->route('customer.orders.show', $order->uuid);
                break;
            default:
                return redirect()->route('admin.orders.show', $order->uuid);
                break;
        }
    }
}
