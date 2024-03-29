<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ImportCsvRequest;
use App\Imports\ProductsImport;
use App\Models\Product;
use App\Services\WarehouseService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ProductImportController extends Controller
{
    public function index()
    {
        return view('admin.product-import.index');
    }

    public function parseCsv(ImportCsvRequest $request)
    {
        auth()->user()->importQueue()->delete();

        Excel::import(new ProductsImport, $request->file('csv'));

        return redirect()->route('admin.product-import.confirm-csv');
    }

    public function confirmCsv()
    {
        $importQueue = auth()->user()->importQueue();
        if ($importQueue->doesntExist()) {
            abort(403);
        }

        $importQueue = $importQueue->get();

        $importQueue = $importQueue->map(function ($item) {
            $product = Product::query()->where('code', $item->code)->first();
            if ($product) {
                $item->found = true;
                $item->product = $product;
            }
            return $item;
        });

        return view('admin.product-import.confirm', compact('importQueue'));
    }

    public function doImport()
    {
        $importQueue = auth()->user()->importQueue();
        if ($importQueue->doesntExist()) {
            abort(403);
        }
        $importQueue = $importQueue->get();

        // delete not existing products
        // Product::query()->whereNotIn('name', $importQueue->pluck('name'))->delete();

        foreach ($importQueue as $item) {
            $product = Product::where('code', $item->code);
            // $product = Product::where('name', $item->name);

            $data = [
                'name' => $item->name,
                'price' => $item->price,
                'units' => $item->units,
                'prime_cost' => $item->price,
                'code' => $item->code,
            ];

            if ($product->exists()) {
                $product = $product->first();
                $product->update($data);
            } else {
                /** @var Product $product */
                $product = Product::query()->create($data);
                WarehouseService::attachProduct($product);
            }

            if ($item->is_virtual) {
                foreach ($product->warehouses as $warehouse) {
                    if ($warehouse->id === (int)setting('warehouse.virtual')) {
                        $warehouse->pivot->update([
                            'enabled' => true,
                        ]);
                    } else {
                        $warehouse->pivot->update([
                            'enabled' => false,
                        ]);
                    }
                }
            }
        }

        auth()->user()->importQueue()->delete();

        return redirect()->route('admin.product.index')->with('status', 'Import success');
    }
}
