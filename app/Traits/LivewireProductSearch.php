<?php

namespace App\Traits;

use App\Models\Product;

trait LivewireProductSearch
{
    public $productSearchInput = '';
    public $productSearchResults = null;
    public $showProductSearchResults = false;

    public function updatedProductSearchInput()
    {
        $this->productSearchResults = Product::query()
            ->where('name', 'like', '%' . $this->productSearchInput . '%')
            ->orWhere('code', 'like', '%' . $this->productSearchInput . '%')
            ->limit(10)
            ->get();

        if ($this->productSearchInput === '') {
            $this->showProductSearchResults = false;
        } else {
            $this->showProductSearchResults = true;
        }
    }

    public function selectProductSearchResultBefore(Product $product) {
        $this->productSearchInput = '';
        $this->showProductSearchResults = false;
        $this->selectProductSearchResult($product);
    }

    public function selectProductSearchResult(Product $product) {
        return redirect()->route('admin.product.show', $product);
    }
}
