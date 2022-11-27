<?php

namespace App\Exports;

use App\Models\Product;
use App\Services\ProductService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductsExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Product::all();
    }

    public function headings(): array
    {
        return [
            'name',
            'code',
            'price',
            'prime_cost',
            'units',
            'is_virtual',
        ];
    }

    public function map($product): array
    {
        return [
            $product->name,
            $product->code,
            $product->price,
            $product->prime_cost,
            $product->units,
            ProductService::isVirtual($product) ? '1' : '0',
        ];
    }
}
