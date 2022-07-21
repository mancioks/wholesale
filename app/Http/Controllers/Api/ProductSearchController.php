<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductSearchController extends Controller
{
    public function results(Request $request)
    {
        $searchQuery = $request->criteria;

        $products = [];
        $entities = Product::search($searchQuery)->orderBy('id', 'desc')->take(50)->get();

        foreach ($entities as $entity) {
            $products[] = [
                'id' => $entity->id,
                'name' => $entity->name,
                'image' => env('APP_URL') . '/' . $entity->image->name,
                'price' => price_format($entity->price) . '€',
            ];
        }

        return json_encode($products);
    }
}
