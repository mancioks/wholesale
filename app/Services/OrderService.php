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

        $user = auth()->user();
        if ($user->acting()->exists()) {
            $user = $user->acting;
        }

        if ($user->details()->exists()) {
            $details['company_name'] = $user->details->company_name ?: '-';
            $details['address'] = $user->details->address ?: '-';
            $details['registration_code'] = $user->details->registration_code ?: '-';
            $details['vat_number'] = $user->details->vat_number ?: '-';
            $details['phone_number'] = $user->details->phone_number ?: '-';
        } else {
            $details['company_name'] = '-';
            $details['address'] = '-';
            $details['registration_code'] = '-';
            $details['vat_number'] = '-';
            $details['phone_number'] = '-';
        }

        return Order::query()->create([
            'user_id' => $user->id,
            'status_id' => 1,
            'discount' => 0,
            'pvm' => $request->post('invoice_to_other') ? setting('pvm') : $user->pvm_size,
            'total' => auth()->user()->total,
            'payment_method_id' => $request->post('payment_method'),
            'payment_status_id' => PaymentStatus::WAITING,
            'vat_number' => 0,
            'warehouse_id' => $request->post('warehouse_id'),
            'message' => $request->post('message'),
            'company_details' => setting('company.details'),
            'customer_name' => $request->post('invoice_to_other') ? $request->post('name') : $user->name,
            'customer_email' => $request->post('invoice_to_other') ? $request->post('email') : $user->email,
            'customer_company_name' => $request->post('invoice_to_other') ? $request->post('company_name') : $details['company_name'],
            'customer_company_address' => $request->post('invoice_to_other') ? $request->post('address') : $details['address'],
            'customer_company_registration_code' => $request->post('invoice_to_other') ? $request->post('registration_code') : $details['registration_code'],
            'customer_company_vat_number' => $request->post('invoice_to_other') ? $request->post('vat_number') : $details['vat_number'],
            'customer_company_phone_number' => $request->post('invoice_to_other') ? $request->post('phone_number') : $details['phone_number'],
            'created_by' => auth()->user()->acting()->exists() ? auth()->user()->id : null,
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
                'prime_cost' => $product->prime_cost,
            ]);
        }

        CartService::clearCart(auth()->user());
    }

    public function getOrders($user, $params = []) {
        if(isset($params['filters']['status']) && isset($this->getFilters()[$params['filters']['status']])) {
            $filterStatusId = [$params['filters']['status']];
        }
        switch ($user->role->id) {
            case Role::CUSTOMER:
                $orders = $user->orders();
                if(isset($filterStatusId)) {
                    $orders->whereIn('status_id', $filterStatusId);
                }
                break;

            case Role::WAREHOUSE:
                $orders = Order::query();
                if(isset($filterStatusId)) {
                    $orders->whereIn('status_id', $filterStatusId);
                } else {
                    $orders->whereIn('status_id', [
                        Status::ACCEPTED,
                        Status::PREPARING,
                        Status::PREPARED,
                        Status::TAKEN,
                        Status::DONE,
                    ]);
                }
                break;

            case Role::ADMIN:
            case Role::SUPER_ADMIN:
                $orders = Order::query();
                if(isset($filterStatusId)) {
                    $orders->whereIn('status_id', $filterStatusId);
                }
                break;
        }

        return $orders->orderBy('id', 'desc')->get();
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

    public function getFilters()
    {
        $filters = [
            Status::CREATED => 'Created',
            Status::ACCEPTED => 'Accepted',
            Status::DECLINED => 'Declined',
            Status::CANCELED => 'Canceled',
            Status::PREPARING => 'Preparing',
            Status::PREPARED => 'Prepared',
            Status::TAKEN => 'Taken',
            Status::DONE => 'Done',
        ];

        $userFilters = [];

        switch (auth()->user()->role->id) {
            case Role::CUSTOMER:
            case Role::ADMIN:
            case Role::SUPER_ADMIN:
                $userFilters = [
                    Status::CREATED => $filters[Status::CREATED],
                    Status::ACCEPTED => $filters[Status::ACCEPTED],
                    Status::DECLINED => $filters[Status::DECLINED],
                    Status::CANCELED => $filters[Status::CANCELED],
                    Status::PREPARING => $filters[Status::PREPARING],
                    Status::PREPARED => $filters[Status::PREPARED],
                    Status::TAKEN => $filters[Status::TAKEN],
                    Status::DONE => $filters[Status::DONE],
                ];
                break;

            case Role::WAREHOUSE:
                $userFilters = [
                    Status::ACCEPTED => $filters[Status::ACCEPTED],
                    Status::PREPARING => $filters[Status::PREPARING],
                    Status::PREPARED => $filters[Status::PREPARED],
                    Status::TAKEN => $filters[Status::TAKEN],
                    Status::DONE => $filters[Status::DONE],
                ];
                break;

        }

        return $userFilters;
    }
}
