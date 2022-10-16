<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWarehouseRequest;
use App\Models\Product;
use App\Models\Warehouse;
use App\Services\CartService;
use App\Services\WarehouseService;

class WarehouseController extends Controller
{
    public function index()
    {
        $warehouses = Warehouse::all();
        return view('warehouse.index', compact('warehouses'));
    }

    public function destroy(Warehouse $warehouse)
    {
        $warehouse->delete();

        return redirect()->route('warehouse.index')->with('status', __('Warehouse deleted'));
    }

    public function create()
    {
        return view('warehouse.create');
    }

    public function store(StoreWarehouseRequest $request)
    {
        /** @var Warehouse $warehouse */
        $warehouse = Warehouse::query()->create($request->validated() + ['active' => $request->post('active') ? 1:0,]);

        WarehouseService::attachWarehouse($warehouse);

        return redirect()->route('warehouse.index')->with('status', __('Warehouse created'));
    }

    public function disableAllProducts(Warehouse $warehouse)
    {
        /** @var Product $product */
        foreach ($warehouse->products as $product) {
            $product->pivot->update(['enabled' => false]);
        }

        return redirect()->back()->with('status', 'All products disabled');
    }

    public function enableAllProducts(Warehouse $warehouse)
    {
        /** @var Product $product */
        foreach ($warehouse->products as $product) {
            $product->pivot->update(['enabled' => true]);
        }

        return redirect()->back()->with('status', 'All products enabled');
    }

    public function edit(Warehouse $warehouse)
    {
        return view('warehouse.edit', compact('warehouse'));
    }

    public function update(StoreWarehouseRequest $request, Warehouse $warehouse)
    {
        $warehouse->update($request->validated() + ['active' => $request->post('active') ? 1:0,]);

        return redirect()->back()->with('status', __('Warehouse updated'));
    }

    public function setWarehouse(Warehouse $warehouse)
    {
        CartService::clearCart(auth()->user());

        if ($warehouse->active) {
            auth()->user()->update(['warehouse_id' => $warehouse->id]);
            return redirect()->route('order.create')->with('status', 'Warehouse changed');
        }

        return redirect()->route('order.create')->withErrors(['Warehouse not active']);
    }
}
