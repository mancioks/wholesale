<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWarehouseRequest;
use App\Models\Warehouse;
use Illuminate\Http\Request;

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
        Warehouse::query()->create($request->validated() + ['active' => $request->post('active') ? 1:0,]);

        return redirect()->route('warehouse.index')->with('status', __('Warehouse created'));
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
}
