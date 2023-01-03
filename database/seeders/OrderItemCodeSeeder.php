<?php

namespace Database\Seeders;

use App\Models\OrderItem;
use Illuminate\Database\Seeder;

class OrderItemCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $orderItems = OrderItem::all();

        foreach ($orderItems as $orderItem) {
            if ($orderItem->product) {
                $orderItem->update(['code' => $orderItem->product->code]);
            }
        }
    }
}
