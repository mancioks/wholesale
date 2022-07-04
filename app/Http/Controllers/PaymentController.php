<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentRequest;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentStatus;
use App\Services\OrderService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::all();
        return view('payment.index', compact('payments'));
    }

    public function store(Order $order, StorePaymentRequest $request, OrderService $service)
    {
        Payment::query()->create($request->validated() + ['order_id' => $order->id]);

        $service->updateOrderPaymentStatus($order);

        return redirect()->back()->with('status', 'Payment added');
    }
}
