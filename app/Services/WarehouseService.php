<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Warehouse;

class WarehouseService
{
    public static function attachProduct(Product $product)
    {
        $warehouses = Warehouse::all();

        foreach ($warehouses as $warehouse) {
            if (!$warehouse->products->contains($product->id)) {
                $warehouse->products()->attach($product);
            }
        }

        return $product;
    }

    public static function attachWarehouse(Warehouse $warehouse)
    {
        $products = Product::all();

        foreach ($products as $product) {
            if (!$product->warehouses->contains($warehouse->id)) {
                $product->warehouses()->attach($warehouse);
            }
        }

        return $warehouse;
    }
}
