<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateInventorizationRequest;
use App\Models\Inventorization;
use App\Models\InventorizationItem;
use App\Models\Order;
use App\Models\Warehouse;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class InventorizationController extends Controller
{
    public function index()
    {
        $warehouses = Warehouse::all();
        return view('admin.inventorization', compact('warehouses'));
    }

    public function add(CreateInventorizationRequest $request)
    {
        $inventorization = Inventorization::query()->create(
            $request->validated() + ['user_id' => auth()->user()->id]
        );

        return redirect()->route('admin.inventorization.show', $inventorization)->with('status', 'Inventorization created');
    }

    public function show(Inventorization $inventorization)
    {
        return view('admin.inventorization.show', compact('inventorization'));
    }

    public function destroy(Inventorization $inventorization)
    {
        $inventorization->delete();

        return redirect()->route('admin.inventorization')->with('status', 'Deleted');
    }

    public function addAll(Inventorization $inventorization)
    {
        foreach ($inventorization->warehouse->products as $product) {
            if (
                $product->pivot->enabled
                && $inventorization->items()->where('code', $product->code)->doesntExist()
            ) {
                InventorizationItem::query()->create([
                    'name' => $product->name,
                    'units' => $product->units,
                    'code' => $product->code,
                    'inventorization_id' => $inventorization->id,
                ]);
            }
        }

        return redirect()->route('admin.inventorization.show', $inventorization)->with('status', 'Products added');
    }

    public function export(Inventorization $inventorization, string $type)
    {
        $method = 'export' . ucfirst(strtolower($type));

        if (method_exists($this, $method)) {
            return $this->$method($inventorization);
        }

        return abort(404);
    }

    public function exportPdf(Inventorization $inventorization)
    {
        return PDF::loadView('pdf.inventorization', compact('inventorization'))->stream();
    }
}
