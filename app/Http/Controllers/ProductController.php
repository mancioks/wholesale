<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportCsvRequest;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Imports\ProductsImport;
use App\Models\Image;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search_query = $request->get('query');
        $products = Product::search($search_query)->orderBy('id', 'desc')->paginate(30);
        return view('product.index', compact('products', 'search_query'));
    }

    public function create()
    {
        return view('product.create');
    }

    public function store(StoreProductRequest $request)
    {
        $product = Product::query()->create($request->validated());

        $file = Storage::disk('uploads')->put('products', $request->file('image'));

        Image::query()->create([
            'name' => Setting::get('products.path').$file,
            'product_id' => $product->id,
        ]);

        return redirect()->back()->with('status', 'Product created');
    }

    public function edit(Product $product)
    {
        return view('product.edit', compact('product'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->validated());

        if ($request->file('image')) {
            if ($product->photo()->exists()) {
                $product->photo()->delete();
            }

            $file = Storage::disk('uploads')->put('products', $request->file('image'));

            Image::query()->create([
                'name' => Setting::get('products.path').$file,
                'product_id' => $product->id,
            ]);
        }

        return redirect()->route('product.index')->with('status', 'Product updated');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return redirect()->route('product.index')->with('status', 'Product deleted');
    }

    public function import()
    {
        return view('product.import');
    }

    public function parseCsv(ImportCsvRequest $request)
    {
        auth()->user()->importQueue()->delete();

        Excel::import(new ProductsImport, $request->file('csv'));

        return redirect()->route('product.import.confirm');
    }

    public function confirmCsv()
    {
        $importQueue = auth()->user()->importQueue();
        if ($importQueue->doesntExist()) {
            abort(403);
        }
        $importQueue = $importQueue->get();

        return view('product.import_confirm', compact('importQueue'));
    }

    public function doImport()
    {
        $importQueue = auth()->user()->importQueue();
        if ($importQueue->doesntExist()) {
            abort(403);
        }
        $importQueue = $importQueue->get();

        foreach ($importQueue as $item) {
            $product = Product::where('name', $item->name);

            $data = [
                'name' => $item->name,
                'price' => $item->price,
                'units' => $item->units,
            ];

            if ($product->exists()) {
                $product->first()->update($data);
            } else {
                Product::query()->create($data);
            }
        }

        auth()->user()->importQueue()->delete();

        return redirect()->route('product.index')->with('status', 'Import success');
    }
}
