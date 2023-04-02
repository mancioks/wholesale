<?php

namespace Database\Seeders;

use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PrimeCostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = Product::all();

        foreach ($products as $product) {
            $this->command->info(sprintf('Updating prime_cost for Product %s (%s) from %s to %s', $product->name, $product->code, $product->prime_cost, $product->price));
            $product->prime_cost = $product->price;
            $product->save();
        }

        $orderItems = OrderItem::all();

        foreach ($orderItems as $orderItem) {
            if ($orderItem->product()->exists()) {
                $this->command->info(sprintf('Updating prime_cost for OrderItem %s (%s) from %s to %s', $orderItem->name, $orderItem->code, $orderItem->prime_cost, $orderItem->product->prime_cost));
                $orderItem->prime_cost = $orderItem->product->prime_cost;
                $orderItem->save();
            } else {
                $this->command->warn(sprintf('OrderItem %s (%s) has no product, skipping', $orderItem->name, $orderItem->code));
            }
        }
    }
}
