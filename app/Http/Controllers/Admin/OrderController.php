<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        return view('admin.orders');
    }

    public function show(Order $order)
    {
        $paymentMethods = PaymentMethod::all();

        return view('admin.order.show', compact('order', 'paymentMethods'));
    }
}
