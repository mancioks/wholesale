<?php

namespace App\Services;

use App\Models\Product;

class ProductService
{
    public static function isVirtual(Product $product)
    {
        $virtualWarehouseId = setting('warehouse.virtual');
        $count = 0;
        $found = false;

        foreach ($product->warehouses as $warehouse) {
            if (!$warehouse->pivot->enabled) {
                continue;
            }

            if ($warehouse->id == $virtualWarehouseId) {
                $found = true;
            }

            $count++;
        }

        if ($count == 1 && $found || $count == 0) {
            return true;
        }

        return false;
    }
}
