<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddToCartRequest;
use App\Http\Requests\UpdateCartRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        return view('cart.index');
    }

    public function addToCart(AddToCartRequest $request, Product $product)
    {
        $user = auth()->user();

        if ($user->cart()->where('product_id', $product->id)->exists()) {
            $user->cart()->find($product)->pivot->increment('qty', $request->post('qty'));
        } else {
            $user->cart()->attach($product, ['qty' => $request->post('qty')]);
        }

        return redirect()->back()->with('status', 'Ok');
    }

    public function removeFromCart(Product $product)
    {
        $user = auth()->user();

        if ($user->cart()->where('product_id', $product->id)->exists()) {
            $user->cart()->detach($product);
            return redirect()->back()->with('status', 'Ok');
        }

        return abort(404);
    }

    public function update(UpdateCartRequest $request)
    {
        foreach (array_combine($request->post('item'), $request->post('qty')) as $item => $qty) {
            auth()->user()->cart()->find($item)->pivot->update(['qty' => $qty]);
        }

        return redirect()->back()->with('status', 'Cart updated');
    }
}
