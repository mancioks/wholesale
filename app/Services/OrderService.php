<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentStatus;
use Illuminate\Http\Request;

class OrderService
{
    private function checkUserCartNotEmpty()
    {
        return auth()->user()->cart()->exists();
    }

    public function create(Request $request)
    {
        if (!$this->checkUserCartNotEmpty()) {
            abort(403);
        }

        return Order::query()->create([
            'user_id' => auth()->id(),
            'status_id' => 1,
            'discount' => 0,
            'pvm' => auth()->user()->pvm_size,
            'total' => auth()->user()->total,
            'payment_method_id' => $request->post('payment_method'),
            'payment_status_id' => PaymentStatus::WAITING,
        ]);
    }

    public function attachProducts($order)
    {
        foreach (auth()->user()->cart as $product) {
            OrderItem::query()->create([
                'order_id' => $order->id,
                'name' => $product->name,
                'price' => $product->price,
                'product_id' => $product->id,
                'qty' => $product->pivot->qty,
            ]);

            auth()->user()->cart()->detach($product);
        }
    }
}