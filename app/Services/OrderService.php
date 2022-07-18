<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentStatus;
use App\Models\Role;
use App\Models\Status;
use App\Models\User;
use Illuminate\Http\Request;

class OrderService
{
    public function create(Request $request)
    {
        if (!(auth()->user()->cart()->exists())) {
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
            'vat_number' => 0,
            'warehouse_id' => $request->post('warehouse_id'),
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
                'units' => $product->units,
            ]);

            auth()->user()->cart()->detach($product);
        }
    }

    public function getOrders($user) {

        switch ($user->role->id) {
            case Role::CUSTOMER:
                $orders = $user->orders()->orderBy('id', 'desc')->get();
                break;

            case Role::WAREHOUSE:
                $orders = Order::whereIn('status_id', [
                    Status::ACCEPTED,
                    Status::PREPARING,
                    Status::PREPARED,
                    Status::DONE,
                ])->orderBy('id', 'desc')->get();
                break;

            case Role::ADMIN:
            case Role::SUPER_ADMIN:
                $orders = Order::orderBy('id', 'desc')->get();
                break;
        }

        return $orders;
    }

    public function updateOrderPaymentStatus(Order $order)
    {
        $order->refresh();

        if($order->paid_total < $order->total) {
            $order->update(['payment_status_id' => PaymentStatus::PARTLY_PAID]);
        } else {
            $order->update(['payment_status_id' => PaymentStatus::PAID]);
        }
    }
}
