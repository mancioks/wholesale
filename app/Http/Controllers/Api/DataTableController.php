<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Warehouse;
use App\Support\OrderListClassName;
use Illuminate\Http\Request;

class DataTableController extends Controller
{
    public function warehouses()
    {
        return datatables(Warehouse::query()->select('id', 'name', 'active'))->toJson();
    }

    public function orders()
    {
        return datatables(
            Order::query()->select('id', 'total', 'user_id', 'status_id', 'created_at', 'updated_at')->get())
            ->addColumn('user.name', function (Order $order) {
                return $order->user->name;
            })
            ->addColumn('statuscolor', function (Order $order) {
                return (new OrderListClassName($order))->get();
            })
            ->addColumn('status.name', function (Order $order) {
                return __($order->status->name);
            })
            ->editColumn('created_at', function (Order $order) {
                return $order->created_at->format('Y-m-d H:i:s');
            })
            ->editColumn('updated_at', function (Order $order) {
                return $order->updated_at->format('Y-m-d H:i:s');
            })
            ->addColumn('number', function (Order $order) {
                return $order->number;
            })->toJson();
    }

    public function products()
    {
        return datatables(
            Product::query()->select('id', 'name', 'price', 'units', 'prime_cost'))
            ->addColumn('image', function (Product $product) {
                return $product->image->name;
            })
            ->editColumn('prime_cost', function (Product $product) {
                return $product->prime_cost ?: 0;
            })->toJson();
    }

    public function users()
    {
        return datatables(
            User::query()->select('id', 'name', 'email', 'role_id', 'activated')->get())
            ->editColumn('role_id', function (User $user) {
                return $user->role->name;
            })
            ->editColumn('activated', function (User $user) {
                return $user->activated ? __('Yes') : __('No');
            })->toJson();
    }
}