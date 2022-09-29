<?php

namespace App\Http\Controllers\Api\Sync;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function get()
    {
        $products = [];
        $entities = Product::orderBy('id', 'desc')->get();

        foreach ($entities as $entity) {
            $products[] = [
                'code' => $entity->id,
                'name' => $entity->name,
                'image' => env('APP_URL') . '/' . $entity->image->name,
                'price' => price_format($entity->original_price),
            ];
        }

        return json_encode($products);
    }
}
