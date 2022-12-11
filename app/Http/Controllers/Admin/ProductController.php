<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ProductsExport;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    public function index()
    {
        return view('admin.products');
    }

    public function show(Product $product)
    {
        return view('admin.product.show', compact('product'));
    }

    public function export()
    {
        return Excel::download(new ProductsExport, 'products.xlsx');
    }
}
