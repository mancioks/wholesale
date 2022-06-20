<?php

namespace App\Http\Controllers;

use App\Mail\Admin\OrderCreated;
use App\Mail\Customer\OrderDeclined;
use App\Models\Order;
use App\Models\Role;
use App\Models\Status;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OrderManagerController extends Controller
{
    public function list()
    {
        $orders = Order::orderBy('updated_at', 'desc')->get();
        return view('manage.order.list', compact('orders'));
    }

    public function show(Order $order)
    {
        return view('manage.order.show', compact('order'));
    }

    public function setStatus(Order $order, Status $status)
    {
        if(!array_key_exists($status->id, $order->actions)) {
            return redirect()->back()->withErrors(['Cannot change status, try again']);
        }

        $order->update([
            'status_id' => $status->id,
        ]);

        if($status->id === Status::DECLINED) {
            Mail::to($order->user->email)->send(new OrderDeclined($order));
        }

        return redirect()->back()->with('status', 'Order status updated');
    }
}
