<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Image;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('product.index', compact('products'));
    }

    public function create()
    {
        return view('product.create');
    }

    public function store(StoreProductRequest $request)
    {
        $product = Product::query()->create($request->validated());

        $fileName = Str::slug($request->post('name')) . '-' . time() . '.' . $request->file('image')->extension();
        $path = 'images/uploads/products';

        $nameWithPath = sprintf('/%s/%s', $path, $fileName);

        $request->file('image')->move(public_path($path), $fileName);

        Image::query()->create([
            'name' => $nameWithPath,
            'product_id' => $product->id,
        ]);

        return redirect()->back()->with('status', 'Product created');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function edit(Product $product)
    {
        return view('product.edit', compact('product'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->validated());

        if($request->file('image')) {
            $product->image()->delete();

            $fileName = Str::slug($request->post('name')) . '-' . time() . '.' . $request->file('image')->extension();
            $path = 'images/uploads/products';

            $nameWithPath = sprintf('/%s/%s', $path, $fileName);

            $request->file('image')->move(public_path($path), $fileName);

            Image::query()->create([
                'name' => $nameWithPath,
                'product_id' => $product->id,
            ]);
        }

        return redirect()->back()->with('status', 'Product updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return redirect()->route('product.index')->with('status', 'Product deleted');
    }
}
