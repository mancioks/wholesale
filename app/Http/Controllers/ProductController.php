<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportCsvRequest;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Image;
use App\Models\ImportQueue;
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

        if ($request->file('image')) {
            if ($product->photo()->exists()) {
                $product->photo()->delete();
            }

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

    public function import()
    {
        return view('product.import');
    }

    public function parseCsv(ImportCsvRequest $request)
    {
        $csv = parse_csv($request->file('csv'), ['name', 'price', 'units']);

        if ($csv) {
            auth()->user()->importQueue()->delete();

            foreach ($csv["rows"] as $row) {
                ImportQueue::query()->create($row + ['user_id' => auth()->id()]);
            }

            return redirect()->route('product.import.confirm');
        } else {
            return redirect()->back()->withErrors(['Something wrong with structure']);
        }
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
