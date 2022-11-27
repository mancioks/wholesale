<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = Product::all();
        foreach ($products as $product) {
            if ($product->code) {
                continue;
            }

            $product->update([
                'code' => 'P' . str_pad($product->id, 5, '0', STR_PAD_LEFT)
            ]);
        }
    }
}
