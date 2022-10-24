<?php

namespace App\Http\Livewire\Admin;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class CreateOrder extends Component
{
    public Collection $products;
    public $productQty;
    public $searchQuery;
    public Collection $searchResults;

    public function mount()
    {
        $this->products = new Collection();
        $this->productQty = [];
        $this->searchQuery = '';
        $this->searchResults = new Collection();
    }

    public function updatedSearchQuery()
    {
        if ($this->searchQuery && $this->searchQuery !== '') {
            $this->searchResults = Product::search($this->searchQuery)->take(5)->get();
        }
    }

    public function add($productId)
    {
        $this->searchQuery = '';
        $this->searchResults = new Collection();

        $product = Product::query()->where('id', $productId)->first();
        if (!$this->products->find($product)) {
            $this->products->add($product);
            $this->productQty[$product->id] = 1;
        } else {
            if (array_key_exists($product->id, $this->productQty)) {
                $this->productQty[$product->id]++;
            } else {
                $this->productQty[$product->id] = 1;
            }
        }
    }

    public function render()
    {
        return view('livewire.admin.create-order');
    }
}
