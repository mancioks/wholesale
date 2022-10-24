<?php

namespace App\Services;

use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Status;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserService
{
    public static function getUserPopularProducts(User $user, int $limit = 10)
    {
        $orders = $user->orders()->whereNotIn('status_id', [Status::CANCELED, Status::DECLINED]);

        $ordersId = $orders->pluck('id')->toArray();

        if (empty($ordersId)) {
            return [];
        }

        $items = OrderItem::query()->whereIn('order_id', $ordersId)->get();

        $userItems = [];
        foreach ($items as $item) {
            if (Product::query()->where(['id' => $item->product_id])->exists()) {
                if (!array_key_exists($item->product_id, $userItems)) {
                    $userItems[$item->product_id] = 0;
                }
                $userItems[$item->product_id] += $item->qty;
            }
        }

        if (empty($userItems)) {
            return [];
        }

        arsort($userItems);

        $productIds = array_keys(array_slice($userItems, 0, $limit, true));
        $productOrderIds = implode(',', $productIds);

        if (empty($productIds)) {
            return [];
        }

        $products = Product::query()->whereIn('id', $productIds)->orderByRaw(DB::raw("FIELD(id, $productOrderIds)"))->get();

        return $products;
    }
}
