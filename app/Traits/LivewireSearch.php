<?php

namespace App\Traits;

use App\Models\Product;

trait LivewireSearch
{
    public $searchInput = '';
    public $searchResults = null;
    public $showSearchResults = false;

    protected $searchClass = Product::class;
    protected $searchLimit = 10;
    protected $searchColumns = ['name', 'code'];

    public function updatedSearchInput()
    {
        $query = $this->searchClass::query()
            ->where('name', 'like', '%' . $this->searchInput . '%')
            ->orWhere('code', 'like', '%' . $this->searchInput . '%')
            ->limit(10)
            ->get();

        // $this->searchResults =

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
