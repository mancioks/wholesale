<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class ProductSearchComponent extends Component
{
    public $searchQuery;
    public $searchResults;
    public $products;

    public function mount()
    {
        $this->searchQuery = '';
        $this->searchResults = new Collection();
        $this->products = [];
    }

    public function updatedSearchQuery()
    {
        if ($this->searchQuery && $this->searchQuery !== '') {
            //$this->searchResults = $this->products[0]->search($this->searchQuery)->take(8)->get();
        } else {
            $this->searchResults = new Collection();
        }
    }

    public function render()
    {
        return view('livewire.product-search-component');
    }
}
